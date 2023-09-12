<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use App\Models\BarPrice;
use App\Models\BarRoom;
use App\Models\HotelRoomSurcharge;
use App\Models\RoomHotel;
use App\Models\Vendor;
use App\Models\ContractPrice;
use App\Models\ContractRate;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurchargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = Auth::id();

        $vendor = Vendor::query()->where('user_id',$userid)->with('users')->first();


        $ContractPrice = ContractPrice::where('contract_prices.user_id', $userid)
            ->with('contractrate')
            ->with('room')
            ->orderBy('recom_price', 'asc')
            ->join('room_hotels', 'contract_prices.room_id', '=', 'room_hotels.id')
            ->get();

        $ContractRate1 = ContractRate::query()->where('user_id',$userid)->where('vendor_id',$vendor->id)->where('rolerate',1)->first();

        $ContractRate = ContractRate::query()->where('user_id',$userid)->where('vendor_id',$vendor->id)->get();

        // dd($ContractPrice);

        if($ContractPrice->isEmpty()){
            $room = RoomHotel::query()->where('user_id',$userid)->get();
        }else{
            $room = ContractPrice::where('contract_prices.user_id', $userid)
            ->where('contract_prices.contract_id',$ContractRate1->id)
            ->with('contractrate')
            ->with('room')
            ->orderBy('recom_price', 'asc')
            ->join('room_hotels', 'contract_prices.room_id', '=', 'room_hotels.id')
            ->get();
        }


        $default_selected_hotel_room = collect($room)->first();
        // dd($room,$default_selected_hotel_room);

        return inertia('Vendor/MenageRoom/Surcharge/Index', [
            'default_selected_hotel_room' => $default_selected_hotel_room,
            'hotel_rooms' => $room,
            'vendor' => $vendor,
            'contractrate' => $ContractRate
        ]);
    }

    public function load_dates(Request $request, $hotel_room_id): JsonResponse
    {
        $from = date('Y-m-d', strtotime($request->start));
        $to = date('Y-m-d', strtotime($request->end));

        $period = CarbonPeriod::create($from, $to);

        $data = [];
        $userid = Auth::id();

        foreach ($period as $date) {
            $hotel_room_surcharge = HotelRoomSurcharge::query()
                ->where('room_hotel_id', $hotel_room_id)
                ->where('start_date', $date)
                ->first();

            // $ContractPrice = ContractPrice::where('room_id', $hotel_room_id)
            //     ->where('user_id', $userid)
            //     ->first();
            $ContractPrice = ContractPrice::where('room_id', $hotel_room_id)
                // ->where('contract_prices.contract_id',$contract_id)
                ->where('contract_prices.user_id', $userid)
                ->with('contractrate')
                ->with('room')
                ->whereHas('contractrate', function ($query) {
                    $query->where('rolerate', 1); // Ganti 'your_column_name' dengan nama kolom yang sesuai
                })
                ->orderBy('recom_price', 'asc')
                ->join('room_hotels', 'contract_prices.room_id', '=', 'room_hotels.id')
                ->first();

            if ($hotel_room_surcharge) {
                // $price = $hotel_room_surcharge->price; // Ambil harga sebagai decimal
                // $formattedPrice = number_format($price, 0, ',', '.'); // Format harga tanpa desimal nol

                if($hotel_room_surcharge->active == 1){
                    $color = 'green';
                }else{
                    $color = 'red';
                }
                $data[] = [
                    'title' => 'Rp '. number_format($hotel_room_surcharge->recom_price, 0, ',', '.'),
                    'start' => date('Y-m-d', strtotime($hotel_room_surcharge->start_date)),
                    'end' => date('Y-m-d', strtotime($hotel_room_surcharge->end_date)),
                    'price' => $hotel_room_surcharge->recom_price,
                    'color'=> $color,
                ];
            } else {
                $data[] = [
                    'title' => 'Rp '.number_format($ContractPrice->recom_price, 0, ',', '.'),
                    'date' => date('Y-m-d', strtotime($date)),
                    'price' => $ContractPrice->recom_price
                ];
            }
        }

        return response()->json($data);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        $request->validate([
            'vendor_id' => 'required',
            'room_hotel_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $hotel_room_surcharge = HotelRoomSurcharge::query()->where('vendor_id', $request->vendor_id)
            ->where('room_hotel_id', $request->room_hotel_id)
            ->where('start_date', $start_date)
            ->first();

        if (! $hotel_room_surcharge) {
            $hotel_room_surcharge = new HotelRoomSurcharge();
        }

        $hotel_room_surcharge->vendor_id = $request->vendor_id;
        $hotel_room_surcharge->room_hotel_id = $request->room_hotel_id;
        $hotel_room_surcharge->start_date = $start_date;
        $hotel_room_surcharge->end_date = $end_date;
        $hotel_room_surcharge->recom_price = $request->price;
        $hotel_room_surcharge->active = $request->active;
        $hotel_room_surcharge->save();

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
