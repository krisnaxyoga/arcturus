<?php

namespace App\Http\Controllers\Agent\Booking;

use App\Models\Setting;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Booking;
use App\Models\PaymentGetwayTransaction;
use App\Models\HotelRoomBooking;

use App\Models\RoomHotel;
use App\Http\Resources\PostResource;
use App\Models\ContractRate;
use App\Models\RoomType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderTransport;

class BookingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $user = User::where('id',$iduser)->with('vendors')->first();
        $booking = Booking::with('users','vendor')->whereNotIn('booking_status', ['-', ''])->where('user_id',$user->vendors->user_id)->orderBy('created_at', 'desc')->get();
        $transport = OrderTransport::where('user_id',$iduser)->get();
        return inertia('Agent/BookingHistory/Index',[
            'data' => $booking,
            'agent' => $user,
            'transport' => $transport,
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
        $cont_id = HotelRoomBooking::where('booking_id',$data->id)->first();
        $conttract = ContractRate::where('id',$cont_id->contract_id)->first();
        $setting = Setting::first();
        $hotelroombooking = HotelRoomBooking::where('booking_id',$data->id)->with('room')->with('contractprice')->with('contractrate')->with('vendors')->get();
        
        foreach($hotelroombooking as $item){
            // $room = RoomHotel::where('id',$item->room_id)->first();
            if($item->room_name == null){
                $item->room_name = $item->room->ratedesc;
                $item->contract_name = $item->contractrate->codedesc;
                $item->benefit_policy = $item->contractrate->benefit_policy;
                $item->other_policy = $item->contractrate->other_policy;
                $item->cencellation_policy = $item->contractrate->cencellation_policy;
                $item->deposit_policy = $item->contractrate->deposit_policy;
                $item->save();
            }
        }

        $transport = OrderTransport::where('booking_id',$id)->with('agenttransport')->first();
        return inertia('Agent/BookingHistory/Detail',[
            'data' => $data,
            'agent' => $agent,
            'setting' => $setting,
            'contract' => $conttract,
            'roombooking' =>$hotelroombooking,
            'transport' => $transport
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
        $transport = OrderTransport::where('booking_id',$id)->first();

        $transport->flight_time = $request->flight_time;
        $transport->time_pickup = $request->time_pickup;
        $transport->pickup_confirmation = $request->pickup_confirmation;
        $transport->save();

        return new PostResource(true, 'Confirmation pickup send to Transport!', $transport);
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
