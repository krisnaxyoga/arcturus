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
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Inertia\Response;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = auth()->user()->id;

        $vendor = Vendor::where('user_id',$userid)->with('users')->first();
        $data = ContractRate::where('user_id',$userid)->get();
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->orderBy('price', 'asc')->get();
        $black = AgentMarkupDetail::where('user_id',$userid)->where('markup_cat_id','blackout')->get();
        $surc = AgentMarkupDetail::where('user_id',$userid)->where('markup_cat_id','surcharges')->get();
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
            'black'=>$black,
            'vendor'=>$vendor
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userid = auth()->user()->id;
        $vendor = Vendor::where('user_id',$userid)->with('users')->first();
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
                'cont' => $contract,
                'vendor' => $vendor
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
                $data->is_active = 1;
                if($contract == false){
                    $data->min_stay = 1;
                    $data->distribute = ["WORLDWIDE"];
                    $data->rolerate = 1;

                }else{
                    $data->min_stay = $request->min_stay;
                    $data->distribute = explode(",",$request->distribute);
                    $data->rolerate = 2;
                }

                $data->pick_day = explode(",", $request->pick_day);
                if($request->cencellation_policy == null){
                    $data->cencellation_policy = "<span>
                    <ul>
                    <li>HIGH season from 01-31 August: 14 days prior to arrival</li>
                    <li>PEAK season from 27Dec - 05Jan: 45 days prior to arrival</li>
                    <li>Except above periods: 72 hours prior to arrival</li>
                    <ul>
                    <li>If cancellation/amendment is made NORMAL/HIGH/PEAK days prior to your arrival date, no fee will be charged</li>
                    <li>If cancellation/amendment is made within NORMAL/HIGH/PEAK days, 1st night’s room rate and tax will be charged
                    </li>
                    <li>In case of no-show, 100% room rate and tax will be charged</li>
                    </ul>
                    <li>Early Bird/Long Stay/Package Rates are Non - refundable</li>
                    </ul>
                    </span>
                    ";
                }else{
                    $data->cencellation_policy = $request->cencellation_policy;
                }
                if ($request->deposit_policy == null){
                    $data->deposit_policy = "<li>Full payment is required upon booking received</li>";
                }else{
                    $data->deposit_policy = $request->deposit_policy;
                }
                if ($request->benefit_policy == null){
                    $data->benefit_policy = "<li>Include daily breakfast for 2 pax</li>";
                }else{
                    $data->benefit_policy = $request->benefit_policy;
                }
                
                $data->except = explode(",",$request->except);
                
                if($request->percentage == null){
                    $data->percentage = 20;
                }else{
                    $data->percentage = $request->percentage;
                }
                
                
                $data->save();

                $interval = 14;
                $startDate = now()->addDays($interval); // Mulai dari 7 hari kemudian
                // $firstTime = true; // Variabel untuk melacak input pertama

                for ($i = 1; $i <= 6; $i++) {
                    $advancepurchase = new AdvancePurchase;
                    $advancepurchase->user_id = $id;
                    $advancepurchase->vendor_id = $vendorid->id;
                    $advancepurchase->contract_id = $data->id;
                    $advancepurchase->day = $interval * $i;
                    // if ($firstTime) {
                    //     $advancepurchase->beginsell = now(); // Input pertama kali adalah tanggal sekarang
                    //     $firstTime = false; // Set variabel firstTime menjadi false setelah input pertama
                    //     $advancepurchase->endsell = now()->addDays($interval * $i)->subDay(); // Karena endsell sehari sebelumnya
                    // } else {
                    $advancepurchase->beginsell = $startDate->copy()->addDays($interval * ($i - 1));
                    $advancepurchase->endsell = $startDate->copy()->addDays($interval * $i)->subDay(); // Karena endsell sehari sebelumnya
                    // }

                    $advancepurchase->is_active = 2;
                    $advancepurchase->numberactive = $i;
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

        $contractroleone= ContractRate::where('user_id',$userid)->where('rolerate',1)->first();

        foreach($barprice as $baritem){
            $cpExists = ContractPrice::where('contract_id',$cont)->where('room_id',$baritem->room_id)->exists();
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
                $data->room_id = $baritem->room_id;
                $data->user_id =  $userid;
                $data->contract_id = $cont;

                if($contract->rolerate == 1){
                    $data->recom_price = $baritem->price * ((100 - $contract->percentage)/100);
                }else{
                    $contprice = ContractPrice::where('contract_id',$contractroleone->id)->where('barprice_id',$baritem->id)->where('room_id', $baritem->room_id)->first();
                    if(!empty($contprice) && $contprice->exists()){
                        $data->recom_price = $contprice->recom_price * ((100 - $contract->percentage)/100);
                   }else{
                       return redirect()->back()->with('success', 'Some rooms have not been filled in your main contract, please check again on the main contract');
                   }
                }

                $data->price = 0;
                $data->is_active = 1;
                $data->barprice_id = $baritem->id;
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
                    $advanceprice->room_id = $baritem->room_id;
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

        $contractroleone= ContractRate::where('user_id',$userid)->where('rolerate',1)->first();
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
            if($contract->rolerate == 1){
                $data->recom_price = $barprice->price * ((100 - $contract->percentage)/100);
            }else{
                $contprice = ContractPrice::where('contract_id',$contractroleone->id)->where('barprice_id',$barprice->id)->where('room_id', $barprice->room_id)->first();
                if(!empty($contprice) && $contprice->exists()){
                     $data->recom_price = $contprice->recom_price * ((100 - $contract->percentage)/100);
                }else{
                    return redirect()->back()->with('success', 'room not found, you have not filled in the room type on your main contract');
                }
            }
            $data->price = 0;

            $data->is_active = 1;
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

        $userid = auth()->user()->id;
        $data = ContractPrice::find($id);

        $contractRate = ContractRate::where('id',$data->contract_id)->first();

        if($contractRate->rolerate == 1){
            $conprice_role_rate2 = ContractPrice::whereHas('contractrate', function ($query) {
                $query->where('rolerate', 2); // Ganti 'your_column_name' dengan nama kolom yang sesuai
            })->where('user_id',$userid)->where('room_id',$data->room_id)->first();
            if($conprice_role_rate2){
                $advprice2 = AdvancePurchasePrice::where('contract_id',$conprice_role_rate2->contract_id)->where('room_id',$data->room_id)->get();
                foreach($advprice2 as $price2){
                  $price2->delete();
                }

               $conprice_role_rate2->delete();
            }
        }

        $data->delete();

        $advprice = AdvancePurchasePrice::where('contract_id',$data->contract_id)->where('room_id',$data->room_id)->get();
        foreach($advprice as $price){
          $price->delete();
        }
        

        return redirect()->back()->with('message', 'Price destroy');
    }

    public function contract_price_is_active($id,$is_active){

        $data = ContractPrice::find($id);
        $data->is_active = $is_active;
        $data->save();

        return redirect()->back()->with('success', 'contract price update status');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userid = auth()->user()->id;
        $vendor = Vendor::where('user_id',$userid)->with('users')->first();
        $price = AgentMarkupSetup::where('user_id',$userid)->get();
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->get();
        $bardata = BarRoom::where('user_id',$userid)->get();
        $country =  get_country_lists();

        $contract = ContractRate::find($id);
        $advancepurchase = AdvancePurchase::where('contract_id',$contract->id)->get();
        $advanceprice = AdvancePurchasePrice::where('contract_id',$contract->id)
                                        ->with('room')
                                        ->orderBy('price', 'asc')
                                        ->get();

        $contone = ContractRate::where('rolerate', 1)->where('user_id', $userid)->first();

        $contractprice = ContractPrice::where('user_id', $userid)
                  ->with('room')
                  ->with('barprice')
                  ->where('contract_id', $id)
                  ->orderBy('recom_price', 'asc')
                  ->get();

        $contractpriceroleone = [];

        foreach ($contractprice as $item) {
            $room_id = $item->room->id;
        
            $contractpriceroleone[] = ContractPrice::where('contract_id', $contone->id)
                ->with('room')
                ->where('room_id', $room_id)
                ->get();
        }

        $contractpriceroleone = collect($contractpriceroleone)->sortBy(function ($item) {
            return $item->first()->recom_price;
        })->values()->all();
        
        return inertia('Vendor/MenageRoom/ContractRate/Edit',[
            'data' => $barprice,
            'bardata' => $bardata,
            'markup' => $price,
            'contract' => $contract,
            'contractprice' => $contractprice,
            'country'=> $country,
            'advancepurchase' => $advancepurchase,
            'advanceprice'=>$advanceprice,
            'vendor' => $vendor,
            'contpriceone' => $contractpriceroleone
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        

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

                if ($data->percentage != $request->percentage) {
                    $barprice = BarPrice::where('user_id', $userid)->get();
                    $contractprice = ContractPrice::where('contract_id', $id)->get();
                    $contone = ContractRate::where('rolerate', 1)->where('user_id', $userid)->first();
                    $contractpriceroleone = ContractPrice::where('contract_id', $contone->id)->get();

                    if ($data->rolerate == 1) {
                        if($barprice->count() == $contractpriceroleone->count()){
                            foreach ($barprice as $key => $baritem) {
                                $cont = ContractPrice::find($contractprice[$key]->id);
                                $cont->recom_price = $baritem->price * ((100 - $request->percentage) / 100);
                                $cont->save();
    
                                $cont2 = ContractPrice::whereHas('contractrate', function ($query) {
                                    $query->where('rolerate', 2); // Ganti 'your_column_name' dengan nama kolom yang sesuai
                                })->with('contractrate')
                                ->where('user_id', $userid)->get();
                                // dd($cont2);
                                foreach($cont2 as $contractpricetwo){
                                    if($contractpricetwo->room_id == $cont->room_id){
                                        $contractpricetwo->recom_price = $cont->recom_price * ((100 - $contractpricetwo->contractrate->percentage) / 100);
                                        $contractpricetwo->save();
                                    }
                                    // $advancePurchase = AdvancePurchase::where('contract_id', $contractpricetwo->contract_id)->first();
                                    // if ($advancePurchase) {
                                    //     $adv = AdvancePurchase::where('contract_id', $contractpricetwo->contract_id)->get();
                                    //     foreach($adv as $advitem){
                                    //         $advancePurchasePrices = AdvancePurchasePrice::where('advance_id', $advitem->id)->get();
                                    //         foreach ($advancePurchasePrices as $advancePurchasePrice) {
                                    //             $advaPrice = AdvancePurchasePrice::find($advancePurchasePrice->id);
                                    //             $advaPrice->delete();
                                    //         }
                                    //     }
                                    // }
                                 }
                            }
                        }else{
                            $cont = ContractPrice::where('contract_id', $id)->whereHas('contractrate', function ($query) {
                                $query->where('rolerate', 1); // Ganti 'your_column_name' dengan nama kolom yang sesuai
                            })->where('user_id', $userid)->get();
                            foreach($cont as $key=>$itemprice){
                                $bar = BarPrice::where('room_id',$itemprice->room_id)->where('user_id', $userid)->first();
                                $cont = ContractPrice::find($itemprice->id);
                                $cont->recom_price = $bar->price * ((100 - $request->percentage) / 100);
                                $cont->save();
                                
                                $cont2 = ContractPrice::whereHas('contractrate', function ($query) {
                                    $query->where('rolerate', 2); // Ganti 'your_column_name' dengan nama kolom yang sesuai
                                })->with('contractrate')->where('user_id', $userid)->get();
                                foreach($cont2 as $contractpricetwo){
                                    if($contractpricetwo->room_id == $itemprice->room_id){
                                        $contractpricetwo->recom_price = $cont->recom_price * ((100 - $contractpricetwo->contractrate->percentage) / 100);
                                        $contractpricetwo->save();
                                    }
                                }
                                
                            }
                        }
                    } else {
                        if($contractpriceroleone->count() == $contractprice->count()){
                            foreach ($contractpriceroleone as $key => $contone) {
                                $cont = ContractPrice::find($contractprice[$key]->id);
                                $cont->recom_price = $contone->recom_price * ((100 - $request->percentage) / 100);
                                $cont->save();
                            }
                        }else{
                            $cont = ContractPrice::where('contract_id', $id)->where('user_id', $userid)->get();
                            foreach($cont as $key=>$itemprice){
                                $bar = ContractPrice::where('room_id',$itemprice->room_id)->whereHas('contractrate', function ($query) {
                                    $query->where('rolerate', 1); // Ganti 'your_column_name' dengan nama kolom yang sesuai
                                })->where('user_id', $userid)->first();
                                // dd($bar);
                                $cont = ContractPrice::find($itemprice->id);
                                $cont->recom_price = $bar->recom_price * ((100 - $request->percentage) / 100);
                                $cont->save();
                            }
                        }
                    }


                    $advancePurchase = AdvancePurchase::where('contract_id', $id)->first();
                    if ($advancePurchase) {
                        $adv = AdvancePurchase::where('contract_id', $id)->get();
                        foreach($adv as $item){
                            $advancePurchasePrices = AdvancePurchasePrice::where('advance_id', $item->id)->get();
                            foreach ($advancePurchasePrices as $advancePurchasePrice) {
                                $advaPrice = AdvancePurchasePrice::find($advancePurchasePrice->id);
                                $advaPrice->delete();
                            }
                        }
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
                $data->benefit_policy = $request->benefit_policy;
                $data->except = explode(",",$request->except);
                $data->distribute = explode(",",$request->distribute);
                $data->percentage = $request->percentage;

                $data->save();

                // Mengumpulkan data yang dibutuhkan sebelumnya
                $contprice = ContractPrice::where('contract_id', $id)->get();
                $advancepurchase = AdvancePurchase::where('contract_id',$id)->get();
                foreach($advancepurchase as $key=>$item){
                    $nilai = [
                        0 => 0.962,
                        1 => 0.925444,
                        2 => 0.890277128,
                        3 => 0.856446597136,
                        4 => 0.823901626444832,
                        5 => 0.792593364639928,
                    ];

                    foreach($contprice as $denden){
                        $advanceprice = new AdvancePurchasePrice;
                        $advanceprice->room_id = $denden->room_id;
                        $advanceprice->user_id = $userid;
                        $advanceprice->vendor_id = $item->vendor_id;
                        $advanceprice->contract_id = $id;
                        $advanceprice->advance_id = $item->id;
                        $advanceprice->price = $denden->recom_price * $nilai[$key];
                        $advanceprice->rolerate = 2;
                        $advanceprice->is_active = $item->is_active;

                        $existingPrice = AdvancePurchasePrice::where('room_id', $advanceprice->room_id)
                            ->where('user_id', $advanceprice->user_id)
                            ->where('vendor_id', $advanceprice->vendor_id)
                            ->where('contract_id', $advanceprice->contract_id)
                            ->where('advance_id', $advanceprice->advance_id)
                            ->first();

                        if (!$existingPrice) {
                            $advanceprice->save();
                        }
                    }
                }

                // Perulangan pada AdvancePurchasePrice



            // return redirect()
            // ->route('contract.index')
            // ->with('success', 'Data saved!');
            return redirect()->back()->with('success', 'Data Updated');
        }
    }

    public function sync_advance_purchase($id){
        $userid = auth()->user()->id;
        $advancePurchase = AdvancePurchase::where('contract_id', $id)->first();
            if ($advancePurchase) {
                $adv = AdvancePurchase::where('contract_id', $id)->get();
                foreach($adv as $item){
                    $advancePurchasePrices = AdvancePurchasePrice::where('advance_id', $item->id)->get();
                    foreach ($advancePurchasePrices as $advancePurchasePrice) {
                        $advaPrice = AdvancePurchasePrice::find($advancePurchasePrice->id);
                        $advaPrice->delete();
                    }
                }
            }
            
        // Mengumpulkan data yang dibutuhkan sebelumnya
        $contprice = ContractPrice::where('contract_id', $id)->get();
        $advancepurchase = AdvancePurchase::where('contract_id',$id)->get();
        foreach($advancepurchase as $key=>$item){
            $nilai = [
                0 => 0.962,
                1 => 0.925444,
                2 => 0.890277128,
                3 => 0.856446597136,
                4 => 0.823901626444832,
                5 => 0.792593364639928,
            ];

            foreach($contprice as $denden){
                $advanceprice = new AdvancePurchasePrice;
                $advanceprice->room_id = $denden->room_id;
                $advanceprice->user_id = $userid;
                $advanceprice->vendor_id = $item->vendor_id;
                $advanceprice->contract_id = $id;
                $advanceprice->advance_id = $item->id;
                $advanceprice->price = $denden->recom_price * $nilai[$key];
                $advanceprice->rolerate = 2;
                $advanceprice->is_active = $item->is_active;

                $existingPrice = AdvancePurchasePrice::where('room_id', $advanceprice->room_id)
                    ->where('user_id', $advanceprice->user_id)
                    ->where('vendor_id', $advanceprice->vendor_id)
                    ->where('contract_id', $advanceprice->contract_id)
                    ->where('advance_id', $advanceprice->advance_id)
                    ->first();

                if (!$existingPrice) {
                    $advanceprice->save();
                }
            }
        }
        return redirect()->back()->with('success', 'advance purchase price has refresh');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus ContractRate
            $userid = auth()->user()->id;
            $data = ContractRate::find($id);

            if($data->rolerate == 1){
               ContractRate::where('user_id',$userid)->delete();
            }

            if ($data) {
                $data->delete();
            }

            // Hapus ContractPrice yang terkait
            $contractPrices = ContractPrice::where('contract_id', $id)->get();
            foreach ($contractPrices as $contractPrice) {
                $contp = ContractPrice::find($contractPrice->id);
                $contp->delete();
            }

            // Hapus AdvancePurchase dan terkaitnya
            $advancePurchase = AdvancePurchase::where('contract_id', $id)->first();
            if ($advancePurchase) {
                $advanceId = $advancePurchase->id;
                $adv = AdvancePurchase::where('contract_id', $id)->get();
                foreach($adv as $item){
                    $advhapus = AdvancePurchase::find($item->id);

                    $advancePurchasePrices = AdvancePurchasePrice::where('advance_id', $item->id)->get();
                    foreach ($advancePurchasePrices as $advancePurchasePrice) {
                        $advaPrice = AdvancePurchasePrice::find($advancePurchasePrice->id);
                        $advaPrice->delete();
                    }

                    $advhapus->delete();
                }
            }

        return redirect()->back()->with('message', 'Data Delete');
    }

    /**
     * ADVANCE PURCHASE FUNCTION
     */

    public function updateadvance(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'day' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data = AdvancePurchase::find($id);
            
            $data->day = $request->day;

            // $beginsell = Carbon::parse($data->beginsell); // Convert beginsell to Carbon object
            $today = Carbon::now(); // Tanggal hari ini

            $interval = $request->day ? intval($request->day) : $data->day; // Calculate the interval based on input day
            // Calculate new endsell based on input day and original beginsell
            $newbeginsell = $today->copy()->addDays($interval); // Calculate endsell based on new beginsell

            $data->beginsell = $newbeginsell; // Set the new endsell value
            $data->save();

            return redirect()->back()->with('success', 'Advance Purchase Updated!');
        }
    }

    public function updateadvancetprice($id,$price){
        // dd($recom);
        $data = AdvancePurchasePrice::find($id);
        $data->price = $price;
        $data->save();

        return redirect()->back()->with('success', 'Price update');
    }

    public function updateadvancetstatus($id, $is_active) {

        $data = AdvancePurchase::find($id);
        $data->is_active = $is_active;
        $data->save();

        $advance = AdvancePurchase::where('contract_id', $data->contract_id)
            ->where('user_id', $data->user_id)
            ->orderBy('numberactive', 'asc')
            ->get();

            // dd($advance);
        $contract = ContractRate::where('id', $data->contract_id)->first();
        $advprice = AdvancePurchasePrice::where('advance_id',$id)->get();
        foreach ($advprice as $item){
            $item->is_active = $is_active;
            $item->save(); 
        }
            
        foreach ($advance as $key => $itm) {

            if ($itm->is_active == 1 && $itm->numberactive >= 1 && $itm->numberactive <= 6) {
                $date3 = AdvancePurchase::where('numberactive', $itm->numberactive)
                    ->where('contract_id', $data->contract_id)
                    ->first();
    
                $date3->beginsell = now()->addDays($date3->day);
                $date3->endsell = Carbon::parse($contract->stayperiod_end);
                $date3->save();
    
                if ($itm->numberactive > 1) {
                    $up_endsell = AdvancePurchase::where('numberactive', $itm->numberactive - 1)
                        ->where('contract_id', $data->contract_id)
                        ->first();
    
                    // $up_endsell->endsell = $date3->beginsell->subDay(1);
                    $up_endsell->endsell = Carbon::parse($contract->stayperiod_end);
                    $up_endsell->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Advancepurchase status update');
    }



    public function destroyadvanceprice(string $id)
    {
        $data = AdvancePurchasePrice::find($id);
        $data->delete();
        return redirect()->back()->with('success', 'Data Delete');
    }

    public function adv_price_is_active($id,$is_active){

        $data = AdvancePurchasePrice::find($id);
        $data->is_active = $is_active;
        $data->save();

        return redirect()->back()->with('success', 'advance purchase update status');
    } 
    
    public function contractrate_is_active($id,$is_active){

        $data = ContractRate::find($id);
        $data->is_active = $is_active;
        $data->save();

        if($is_active == 1){
            $this->addallcontractprice($id);
        }

        return redirect()->back()->with('success', 'contract rate update status');
    }

}
