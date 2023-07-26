<?php

namespace App\Http\Controllers\Agent\Booking;

use App\Models\Setting;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Booking;
use App\Models\PaymentGetwayTransaction;
use App\Models\HotelRoomBooking;
use App\Models\RoomType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $user = User::where('id',$iduser)->with('vendors')->first();
        $booking = Booking::with('users','vendor')->where('user_id',$user->vendors->user_id)->orderBy('created_at', 'desc')->get();
        
        return inertia('Agent/BookingHistory/Index',[
            'data' => $booking,
            'agent' => $user
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function detail(string $id)
    {
        $iduser = auth()->user()->id;
        $agent = User::where('id',$iduser)->with('vendors')->first();
        $data = Booking::where('id',$id)->with('vendor')->with('users')->first();
        //dd($room);

        return inertia('Agent/BookingHistory/Detail',[
            'data' => $data,
            'agent' => $agent
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function invoice(string $id)
    {
        $iduser = auth()->user()->id;
        $agent = Vendor::with('users')->where('user_id',$iduser)->first();
        $booking = Booking::with('users','vendor')
            ->where('user_id',$iduser)
            ->where('id',$id)
            ->first();
        $gateway = PaymentGetwayTransaction::where('booking_id', $id)
            ->where('user_id', $iduser)
            ->first();

        $room = HotelRoomBooking::with('room')
            ->where('booking_id', $id)
            ->first();

        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        //dd($room);

        return inertia('Agent/BookingHistory/Invoice',[
            'data' => $booking,
            'agent' => $agent,
            'gateway' => $gateway,
            'roombooking' => $room,
            'setting' => $setting
        ]);
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

    /**
     * Remove the specified resource from storage.
     */
    public function reports(string $id)
    {
        //
    }
}
