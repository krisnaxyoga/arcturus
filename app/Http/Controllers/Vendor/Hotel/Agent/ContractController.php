<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomHotel;
use App\Models\BarRoom;
use App\Models\BarPrice;
use App\Models\Vendor;
use App\Models\AgentMarkupSetup;
use App\Models\AgentMarkupDetail;
use App\Models\ContractRate;
use App\Models\ContractPrice;

use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $data = ContractRate::where('user_id',$iduser)->get();
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->orderBy('price', 'asc')->get();
        $black = AgentMarkupDetail::where('user_id',$userid)->where('markup_cat_id','blackout')->get();
        $surc = AgentMarkupDetail::where('user_id',$userid)->where('markup_cat_id','surcharges')->get();
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)
            ->with('barroom')
            ->with('room')
            ->orderBy('price', 'asc')
            ->get();
        $barroom = BarRoom::where('user_id',$userid)->get();

        if($barroom->isEmpty()){
            $room = RoomHotel::where('user_id',$userid)->get();
            $is_form = 'add';
            $roomcategory = '';
        }else{
            $room = $barprice;
            $roomcategory = RoomHotel::where('user_id',$userid)->get();
            $is_form = 'edit';
        }

        return inertia('Vendor/MenageRoom/ContractRate/Index',[
            'data'=>$data,
            'roomtype'=>$room,
            'roomcategory' => $roomcategory,
            'form'=>$is_form,
            'barroom' =>$barroom->first(),
            'surcharge'=>$surc,
            'black'=>$black
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userid = auth()->user()->id;
        $price = AgentMarkupSetup::where('user_id',$userid)->first();
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->get();
        $bardata = BarRoom::where('user_id',$userid)->get();
        $country =  get_country_lists();
            return inertia('Vendor/MenageRoom/ContractRate/Create',[
                'data' => $barprice,
                'bardata' => $bardata,
                'markup' => $price,
                'country' => $country
            ]);
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'ratecode' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $id = auth()->user()->id;

                $vendorid = Vendor::where('user_id',$id)->first();
                // dd($vendorid);
                $data =  new ContractRate();
                $data->user_id = $id;
                $data->vendor_id = $vendorid->id;
                $data->ratecode = $request->ratecode;
                $data->codedesc = $request->codedesc;
                $data->stayperiod_begin = $request->stayperiod_begin;
                $data->stayperiod_end = $request->stayperiod_end;
                $data->booking_begin = $request->booking_begin;
                $data->booking_end = $request->booking_end;
                $data->min_stay = $request->min_stay;
                $data->pick_day = explode(",", $request->pick_day);
                $data->cencellation_policy = $request->cencellation_policy;
                $data->deposit_policy = $request->deposit_policy;
                $data->except = explode(",",$request->except);
                $data->distribute = explode(",",$request->distribute);
                $data->save();

                $markup = AgentMarkupSetup::where('user_id',$id)->get();

                if($markup->isEmpty()){
                    $newmarkup = new AgentMarkupSetup();
                    $newmarkup->user_id = $id;
                    $newmarkup->vendor_id = $vendorid->id;
                    $newmarkup->markup_price = $request->minmarkup;
                    $newmarkup->tax = 0;
                    $newmarkup->service = 0;
                    $newmarkup->save();

                }else{
                    $markupupdate = AgentMarkupSetup::find($markup[0]->id);
                    $markupupdate->markup_price = $request->minmarkup;
                    $markupupdate->save();
                }


            return redirect()
            ->route('contract.edit',$data->id)
            ->with('success', 'Data saved!');
        }


    }

    public function addallcontractprice($cont){

        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->get();

        $markup = AgentMarkupSetup::where('user_id',$userid)->get();
        foreach($barprice as $item){
            $cpExists = ContractPrice::where('contract_id',$cont)->where('room_id',$item->room_id)->exists();
            //contoh bila data $cp tidak ditemukan maka if nya seperti ini
            if(!$cpExists){
                $data = new ContractPrice();
                $data->room_id = $item->room_id;
                $data->user_id =  $userid;
                $data->contract_id = $cont;
                $data->recom_price = $item->price * 0.83;
                $data->price = ($item->price * 0.83)+$markup[0]->markup_price;
                $data->barprice_id = $item->id;
                $data->save();
            }
        }

        return redirect()->back()->with('message', 'Room Type add');
    }
    /**
     * Display the specified resource.
     */
    public function addcontractprice(string $id,$cont)
    {
        // dd($id,$cont);
        $barprice = BarPrice::find($id);
        $userid = auth()->user()->id;
        $markup = AgentMarkupSetup::where('user_id',$userid)->first();

            $data = new ContractPrice();
            $data->room_id = $barprice->room_id;
            $data->user_id =  $userid;
            $data->contract_id = $cont;
            $data->recom_price = $barprice->price * 0.83;
            if($markup->markup_price == 0){
                $nilai = $barprice->price * 0.83;
                $hasil = $barprice->price - $nilai + 15000;
                $data->price = $nilai + $hasil;
            }else{
                $data->price = ($barprice->price * 0.83)+$markup->markup_price;
            }

            $data->barprice_id = $id;
            $data->save();

        return redirect()->back()->with('message', 'Room Type add');



        // dd($cont);
    }

    public function updatecontractprice($id,$recom,$price){
        // dd($recom);
        $data = ContractPrice::find($id);
        $data->recom_price = $recom;
        $data->price = $price;
        $data->save();

        return redirect()->back()->with('message', 'Price update');
    }

    public function destroycontractprice($id){
        $data = ContractPrice::find($id);
        $data->delete();

        return redirect()->back()->with('message', 'Price destroy');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userid = auth()->user()->id;
        $price = AgentMarkupSetup::where('user_id',$userid)->get();
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->get();
        $bardata = BarRoom::where('user_id',$userid)->get();
        $country =  get_country_lists();

        $contract = ContractRate::find($id);
        $contractprice = ContractPrice::where('user_id', $userid)
                  ->with('room')
                  ->with('barprice')
                  ->where('contract_id', $id)
                  ->orderBy('price', 'asc')
                  ->get();

        return inertia('Vendor/MenageRoom/ContractRate/Edit',[
            'data' => $barprice,
            'bardata' => $bardata,
            'markup' => $price,
            'contract' => $contract,
            'contractprice' => $contractprice,
            'country'=> $country
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);

        $validator = Validator::make($request->all(), [
            'ratecode' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $userid = auth()->user()->id;
                $data = ContractRate::find($id);
                $data->user_id = $userid;
                $data->ratecode = $request->ratecode;
                $data->codedesc = $request->codedesc;
                $data->stayperiod_begin = $request->stayperiod_begin;
                $data->stayperiod_end = $request->stayperiod_end;
                $data->booking_begin = $request->booking_begin;
                $data->booking_end = $request->booking_end;
                $data->min_stay = $request->min_stay;
                $data->pick_day = explode(",", $request->pick_day);
                $data->cencellation_policy = $request->cencellation_policy;
                $data->deposit_policy = $request->deposit_policy;
                $data->except = explode(",",$request->except);
                $data->distribute = explode(",",$request->distribute);
                $data->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = ContractRate::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data Delete');
    }
}
