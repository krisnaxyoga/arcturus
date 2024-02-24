<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomHotel;
use App\Models\BarRoom;
use App\Models\BarPrice;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

use App\Models\ContractRate;
use App\Models\AdvancePurchase;

class BarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->get();

        return inertia('Vendor/MenageRoom/BarRoom/Index',[
            'data' => $barprice
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)
            ->with('barroom')
            ->with('room')
            ->get();
        $barroom = BarRoom::where('user_id',$userid)->get();

        if($barroom->isEmpty()){
            $data = RoomHotel::where('user_id',$userid)->get();
            return inertia('Vendor/MenageRoom/BarRoom/Create',[
                'data'=>$data
            ]);
        }else{
            return inertia('Vendor/MenageRoom/BarRoom/Edit',[
                'data' => $barprice,
            ]);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
         $validator = Validator::make($request->all(), [
            'barcode' => 'nullable',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $id = auth()->user()->id;

                $vendor = Vendor::where('user_id', $id)->get();
                $data = new BarRoom();
                $data->user_id = $id;
                $data->vendor_id = $vendor[0]->id;
        
                if (!$request->bardesc) {
                    $data->barcode = 'BAR';
                    $data->bardesc = 'HOTEL BEST AVAILABLE RATE';
                } else {
                    $data->barcode = $request->barcode;
                    $data->bardesc = $request->bardesc;
                }
                $data->begindate = $request->begin;
                $data->enddate = $request->end;
                $data->save();
        
                if (!$request->price) {
                    return redirect()->back()->with('success', 'Please first fill in your Room Type on the room types menu.');
                } else {
                    foreach ($request->price as $item) {
                        $bar = new BarPrice();
                        $bar->user_id = $id;
                        $bar->bar_id = $data->id;
                        $bar->room_id = $item['room_id'];
                        $bar->price = $item['price'];
                        $bar->save();
                    }

                    
                $nilai = [
                    0 => [
                        'ratecode' => 'WORLD',
                        'desc' => 'worldwide',
                        'percented' => 20,
                        'minstay' => 1,
                        'rolerate' => 1,
                        'distribute' => ["WORLDWIDE"]
                        ],
                      1 => [
                        'ratecode' => 'WORLD3',
                        'desc' => 'worldwide',
                        'percented' => 5,
                        'minstay' => 3,
                        'rolerate' => 2,
                        'distribute' => ["WORLDWIDE"]
                        ],
                       2 => [
                        'ratecode' => 'WORLD5',
                        'desc' => 'worldwide',
                        'percented' => 10,
                        'minstay' => 5,
                        'rolerate' => 2,
                        'distribute' => ["WORLDWIDE"]
                        ],
                        3 =>[
                        'ratecode' => 'WORLD7',
                        'desc' => 'worldwide',
                        'percented' => 15,
                        'minstay' => 7,
                        'rolerate' => 2,
                        'distribute' => ["WORLDWIDE"]
                        ],
                     4 =>[
                        'ratecode' => 'WORLD9',
                        'desc' => 'worldwide',
                        'percented' => 20,
                        'minstay' => 9,
                        'rolerate' => 2,
                        'distribute' => ["WORLDWIDE"]
                        ], 
                    5 =>[
                        'ratecode' => 'DOM1',
                        'desc' => 'Domestic',
                        'percented' => 10,
                        'minstay' => 1,
                        'rolerate' => 2,
                        'distribute' => ["".$vendor[0]->country.""]
                        ],
                    6 =>[
                        'ratecode' => 'DOM2',
                        'desc' => 'Domestic',
                        'percented' => 15,
                        'minstay' => 3,
                        'rolerate' => 2,
                        'distribute' => ["".$vendor[0]->country.""]
                        ],
                    7 =>[
                        'ratecode' => 'DOM3',
                        'desc' => 'Domestic',
                        'percented' => 20,
                        'minstay' => 5,
                        'rolerate' => 2,
                        'distribute' => ["".$vendor[0]->country.""]
                        ],
                    // 5 =>[
                    //     'ratecode' => 'APAC',
                    //     'desc' => 'ASIAPACIFIC',
                    //     'percented' => 5,
                    //     'minstay' => 1,
                    //     'rolerate' => 2,
                    //     'distribute' => ["China", "Hongkong", "Japan", "Korea", "Malaysia", "Singapore", "Taiwan", "Thailand", "Vietnam", "Philippines", "Australia", "New Zealand"]
                    //     ],
                    // 6 =>[
                    // 'ratecode' => 'APAC3',
                    // 'desc' => 'ASIAPACIFIC',
                    // 'percented' => 10,
                    // 'minstay' => 3,
                    // 'rolerate' => 2,
                    // 'distribute' => ["China", "Hongkong", "Japan", "Korea", "Malaysia", "Singapore", "Taiwan", "Thailand", "Vietnam", "Philippines", "Australia", "New Zealand"]
                    // ],
                    // 7 =>[
                    // 'ratecode' => 'APAC5',
                    // 'desc' => 'ASIAPACIFIC',
                    // 'percented' => 15,
                    // 'minstay' => 5,
                    // 'rolerate' => 2,
                    // 'distribute' => ["China", "Hongkong", "Japan", "Korea", "Malaysia", "Singapore", "Taiwan", "Thailand", "Vietnam", "Philippines", "Australia", "New Zealand"]
                    // ],
                    // 8 =>[
                    // 'ratecode' => 'APAC7',
                    // 'desc' => 'ASIAPACIFIC',
                    // 'percented' => 20,
                    // 'minstay' => 7,
                    // 'rolerate' => 2,
                    // 'distribute' => ["China", "Hongkong", "Japan", "Korea", "Malaysia", "Singapore", "Taiwan", "Thailand", "Vietnam", "Philippines", "Australia", "New Zealand"]
                    // ],
                    // 9 =>[
                    // 'ratecode' => 'DOM',
                    // 'desc' => 'DOMESTIC',
                    // 'percented' => 10,
                    // 'minstay' => 1,
                    // 'rolerate' => 2,
                    // 'distribute' => ["Indonesia"]
                    // ], 
                    // 10 =>[
                    // 'ratecode' => 'DOM3',
                    // 'desc' => 'DOMESTIC',
                    // 'percented' => 15,
                    // 'minstay' => 3,
                    // 'rolerate' => 2,
                    // 'distribute' => ["Indonesia"]
                    // ],
                    // 11 =>[
                    // 'ratecode' => 'DOM5',
                    // 'desc' => 'DOMESTIC',
                    // 'percented' => 20,
                    // 'minstay' => 5,
                    // 'rolerate' => 2,
                    // 'distribute' => ["Indonesia"]
                    // ],
                ];

                foreach ($nilai as $key => $value) {
                    $contract = new ContractRate();
                    $contract->user_id = $id;
                    $contract->vendor_id = $vendor[0]->id;
                    $contract->ratecode = $value['ratecode'];
                    $contract->codedesc = $value['desc'];
                    $contract->stayperiod_begin = $request->begin;
                    $contract->stayperiod_end = $request->end;
                    $contract->booking_begin = $request->begin;
                    $contract->booking_end = $request->end;
                    $contract->is_active = 2;
        
                    $contract->distribute = $value['distribute'];
                    $contract->min_stay = $value['minstay'];
                    $contract->distribute = $value['distribute'];
                    $contract->rolerate = $value['rolerate'];
                    $contract->pick_day = [""];
                    // $contract->cencellation_policy = "<span>
                    // <ul>
                    // <li>HIGH season from 01-31 August: 14 days prior to arrival</li>
                    // <li>PEAK season from 27Dec - 05Jan: 45 days prior to arrival</li>
                    // <li>Except above periods: 72 hours prior to arrival</li>
                    // <ul>
                    // <li>If cancellation/amendment is made NORMAL/HIGH/PEAK days prior to your arrival date, no fee will be charged</li>
                    // <li>If cancellation/amendment is made within NORMAL/HIGH/PEAK days, 1st night’s room rate and tax will be charged
                    // </li>
                    // <li>In case of no-show, 100% room rate and tax will be charged</li>
                    // </ul>
                    // <li>Early Bird/Long Stay/Package Rates are Non - refundable</li>
                    // </ul>
                    // </span>";
                    $contract->cencellation_policy = "<span>
                    <ul>
                    <li>Non - refundable,</li>
                    <li>7days prior to arrival, changeable within the same year with NEW rate apply</li>
                    </ul>
                    </span>
                    ";
                    $contract->deposit_policy = "<li>Full payment is required upon booking received</li>";
                    $contract->benefit_policy = "<li>Include daily breakfast for 2 pax</li>";
        
                    $contract->percentage = $value['percented'];
        
                    $contract->save();
        
                    $interval = 14;
                    $startDate = now()->addDays($interval);
        
                    for ($i = 1; $i <= 6; $i++) {
                        $advancepurchase = new AdvancePurchase();
                        $advancepurchase->user_id = $id;
                        $advancepurchase->vendor_id = $vendor[0]->id;
                        $advancepurchase->contract_id = $contract->id;
                        $advancepurchase->day = $interval * $i;
                        $advancepurchase->beginsell = $startDate->copy()->addDays($interval * ($i - 1));
                        $advancepurchase->endsell = $startDate->copy()->addDays($interval * $i)->subDay();
                        $advancepurchase->is_active = 2;
                        $advancepurchase->numberactive = $i;
                        $advancepurchase->save();
                    }
                }
                    return redirect()
                        ->route('contract.index')
                        ->with('success', 'Data saved!');
                }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->where('bar_id',$id)->with('barroom')->with('room')->get();
        return inertia('Vendor/MenageRoom/BarRoom/Edit',[
            'data' => $barprice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validator = Validator::make($request->all(), [
            'barcode' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

                $data = BarRoom::find($id);
                $data->barcode = $request->barcode;
                $data->bardesc = $request->bardesc;
                $data->begindate = $request->begin;
                $data->enddate = $request->end;
                $data->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    public function barupdate(Request $request, string $id)
    {
         $validator = Validator::make($request->all(), [
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $bar = BarPrice::find($id);
            $bar->price = $request->price;
            $bar->save();

        }
        return redirect()
        ->route('contract.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function bardestroy($id){
        $data = BarPrice::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data deleted!');
    }

    public function cekroom($barid){
        $userid = auth()->user()->id;
        $data = RoomHotel::where('user_id',$userid)->get();
        foreach($data as $item){
            $barpriceExists = BarPrice::where('room_id', $item->id)->exists();
            if(!$barpriceExists){
                $bar =  new BarPrice();
                $bar->user_id = $userid;
                $bar->bar_id = $barid;
                $bar->room_id = $item->id;
                $bar->save();
            }
        }
        return redirect()
        ->route('contract.index')
        ->with('success', 'Room Add Success!');
    }

}
