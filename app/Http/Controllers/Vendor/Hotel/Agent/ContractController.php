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
use App\Models\AdvancePurchase;
use App\Models\AdvancePurchasePrice;

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
        $contract = ContractRate::where('user_id',$userid)->exists();
        
            return inertia('Vendor/MenageRoom/ContractRate/Create',[
                'data' => $barprice,
                'bardata' => $bardata,
                'markup' => $price,
                'country' => $country,
                'cont' => $contract
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
                $contract = ContractRate::where('user_id',$id)->exists();
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
                if($contract == false){
                    $data->min_stay = 1;
                    $data->distribute = ["all"];
                    $data->rolerate = 1;

                }else{
                    $data->min_stay = $request->min_stay;
                    $data->distribute = explode(",",$request->distribute);
                    $data->rolerate = 2;
                }
                
                $data->pick_day = explode(",", $request->pick_day);
                $data->cencellation_policy = $request->cencellation_policy;
                $data->deposit_policy = $request->deposit_policy;
                $data->except = explode(",",$request->except);
                $data->percentage = $request->percentage;
                $data->save();

                $interval = 7;
                $startDate = now()->addDays($interval); // Mulai dari 7 hari kemudian

                for ($i = 1; $i <= 6; $i++) {
                    $advancepurchase = new AdvancePurchase;
                    $advancepurchase->user_id = $id;
                    $advancepurchase->vendor_id = $vendorid->id;
                    $advancepurchase->contract_id = $data->id;
                    $advancepurchase->day = $interval * $i;
                    $advancepurchase->beginsell = $startDate->copy()->addDays($interval * ($i - 1));
                    $advancepurchase->endsell = $startDate->copy()->addDays($interval * $i)->subDay(); // Karena endsell sehari sebelumnya
                    $advancepurchase->is_active = 1;
                    $advancepurchase->save();
                }

            return redirect()
            ->route('contract.edit',$data->id)
            ->with('success', 'Data saved!');
        }


    }

    public function addallcontractprice($cont){

        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->get();

        // $markup = AgentMarkupSetup::where('user_id',$userid)->get();

        $contract = ContractRate::find($cont);

        foreach($barprice as $item){
            $cpExists = ContractPrice::where('contract_id',$cont)->where('room_id',$item->room_id)->exists();
             // ================================== OLD RUMUS ============================================
            //contoh bila data $cp tidak ditemukan maka if nya seperti ini
            // if(!$cpExists){
            //     $data = new ContractPrice();
            //     $data->room_id = $item->room_id;
            //     $data->user_id =  $userid;
            //     $data->contract_id = $cont;
            //     $data->recom_price = $item->price * 0.83;
            //     $data->price = ($item->price * 0.83)+$markup[0]->markup_price;
            //     $data->barprice_id = $item->id;
            //     $data->save();
            // }
            // ================================== OLD RUMUS ============================================

            if(!$cpExists){
                $data = new ContractPrice();
                $data->room_id = $item->room_id;
                $data->user_id =  $userid;
                $data->contract_id = $cont;
                $data->recom_price = $item->price * ((100 - $contract->percentage)/100);
                $data->price = 0;
                $data->barprice_id = $item->id;
                $data->save();

                $advancepurchase = AdvancePurchase::where('contract_id',$contract->id)->get();
                foreach($advancepurchase as $key=>$item){
                    $nilai = [
                        0 => 0.962,
                        1 => 0.925444,
                        2 => 0.890277128,
                        3 => 0.856446597136,
                        4 => 0.823901626444832,
                        5 => 0.792593364639928,
                    ];
    
                    $advanceprice = new AdvancePurchasePrice;
                    $advanceprice->room_id = $item->room_id;
                    $advanceprice->user_id = $userid;
                    $advanceprice->vendor_id = $item->vendor_id;
                    $advanceprice->contract_id = $cont;
                    $advanceprice->advance_id = $item->id;
                    $advanceprice->price = $data->recom_price * $nilai[$key];
                    $advanceprice->rolerate = 2;
                    $advanceprice->is_active = $item->is_active;
                    $advanceprice->save();
                }
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
        $contract = ContractRate::find($cont);

            // $markup = AgentMarkupSetup::where('user_id',$userid)->first();

            //=====================================OLD RUMUS=============================================
            // $data = new ContractPrice();
            // $data->room_id = $barprice->room_id;
            // $data->user_id =  $userid;
            // $data->contract_id = $cont;
            // $data->recom_price = $barprice->price * 0.83;
            // if($markup->markup_price == 0){
            //     $nilai = $barprice->price * 0.83;
            //     $hasil = $barprice->price - $nilai + 15000;
            //     $data->price = $nilai + $hasil;
            // }else{
            //     $data->price = ($barprice->price * 0.83)+$markup->markup_price;
            // }
            //=========================================OLD RUMUS================================================

            $data = new ContractPrice();
            $data->room_id = $barprice->room_id;
            $data->user_id =  $userid;
            $data->contract_id = $cont;
            $data->recom_price = $barprice->price * ((100 - $contract->percentage)/100);
            $data->price = 0;
            

            $data->barprice_id = $id;
            $data->save();

            
            $advancepurchase = AdvancePurchase::where('contract_id',$contract->id)->get();
            foreach($advancepurchase as $key=>$item){
                $nilai = [
                    0 => 0.962,
                    1 => 0.925444,
                    2 => 0.890277128,
                    3 => 0.856446597136,
                    4 => 0.823901626444832,
                    5 => 0.792593364639928,
                ];

                $advanceprice = new AdvancePurchasePrice;
                $advanceprice->room_id = $barprice->room_id;
                $advanceprice->user_id = $userid;
                $advanceprice->vendor_id = $item->vendor_id;
                $advanceprice->contract_id = $contract->id;
                $advanceprice->advance_id = $item->id;
                $advanceprice->price = $data->recom_price * $nilai[$key];
                $advanceprice->rolerate = 2;
                $advanceprice->is_active = $item->is_active;
                $advanceprice->save();
            }
            

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
        $advancepurchase = AdvancePurchase::where('contract_id',$contract->id)->get();
        $advanceprice = AdvancePurchasePrice::where('contract_id',$contract->id)->with('room')->get();

        $contractprice = ContractPrice::where('user_id', $userid)
                  ->with('room')
                  ->with('barprice')
                  ->where('contract_id', $id)
                  ->orderBy('recom_price', 'asc')
                  ->get();

        return inertia('Vendor/MenageRoom/ContractRate/Edit',[
            'data' => $barprice,
            'bardata' => $bardata,
            'markup' => $price,
            'contract' => $contract,
            'contractprice' => $contractprice,
            'country'=> $country,
            'advancepurchase' => $advancepurchase,
            'advanceprice'=>$advanceprice
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

                if($data->percentage != $request->percentage){
                    $barprice = BarPrice::where('user_id',$userid)->get();
                    $contractprice = ContractPrice::where('contract_id',$id)->get();
                    foreach($barprice as $key=>$item){
                        $cont = ContractPrice::find($contractprice[$key]->id);
                        $cont->recom_price = $item->price * ((100 - $request->percentage)/100);
                        $cont->save();
                    }
                }

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
                $data->percentage = $request->percentage;
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
