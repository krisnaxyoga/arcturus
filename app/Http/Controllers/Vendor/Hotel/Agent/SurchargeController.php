<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use App\Models\BarPrice;
use App\Models\BarRoom;
use App\Models\HotelRoomSurcharge;
use App\Models\RoomHotel;
use App\Models\Vendor;
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

        $bar_price = BarPrice::query()->where('user_id',$userid)
            ->with('barroom')
            ->with('room')
            ->orderBy('price', 'asc')
            ->get();

        $bar_room = BarRoom::query()->where('user_id',$userid)->get();

        if ( $bar_room->isEmpty() ) {
            $room = RoomHotel::query()->where('user_id',$userid)->get();
        } else {
            $room = $bar_price;
        }

        $default_selected_hotel_room = collect($room)->first();

        return inertia('Vendor/MenageRoom/Surcharge/Index', [
            'default_selected_hotel_room' => $default_selected_hotel_room,
            'hotel_rooms' => $room,
            'vendor' => $vendor
        ]);
    }

    public function load_dates(Request $request, $hotel_room_id): JsonResponse
    {
        $from = date('Y-m-d', strtotime($request->start));
        $to = date('Y-m-d', strtotime($request->end));

        $period = CarbonPeriod::create($from, $to);

        $data = [];

        foreach ($period as $date) {
            $hotel_room_surcharge = HotelRoomSurcharge::query()
                ->where('room_hotel_id', $hotel_room_id)
                ->where('start_date', $date)
                ->first();

            if ($hotel_room_surcharge) {
                $data[] = [
                    'title' => '$'. $hotel_room_surcharge->price,
                    'date' => date('Y-m-d', strtotime($date)),
                    'price' => $hotel_room_surcharge->price
                ];
            } else {
                $data[] = [
                    'title' => '$0',
                    'date' => date('Y-m-d', strtotime($date)),
                    'price' => 0
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
        $hotel_room_surcharge->price = $request->price;
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
