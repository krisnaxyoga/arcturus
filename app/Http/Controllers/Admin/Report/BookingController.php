<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Booking;
use App\Models\PaymentGetwayTransaction;

use App\Models\OrderTransport;
use App\Models\RoomHotel;
use App\Models\Vendor;
use App\Models\User;
use App\Models\ContractRate;
use App\Models\HotelRoomBooking;
use App\Mail\BookingConfirmation;
use App\Mail\BookingConfirmationHotel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $data = PaymentGetwayTransaction::where('payment_method', 'BANK-TRANSFER')->with('booking')->orderBy('created_at', 'desc')->get();
        // dd($data);

        $isee = PaymentGetwayTransaction::where('payment_method', 'BANK-TRANSFER')->where('is_see',0)->get();
        foreach($isee as $show){
            $show->is_see = 1;
            $show->save();
        }

        return view('admin.booking.index',compact('setting','data'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function confirmation($id)
    {
        $payment = PaymentGetwayTransaction::find($id);
        $payment->status = 200;
        $payment->save();

        $booking = Booking::find($payment->booking_id);
        $booking->booking_status = 'paid';
        $booking->save();

        $tranportbookings = OrderTransport::where('booking_id',$payment->booking_id)->first();

        if($tranportbookings){
             $tranportbookings->booking_status = 'paid';
            $tranportbookings->is_see = 0;
            $tranportbookings->save();
        }


        $hotelbook = HotelRoomBooking::where('booking_id',$payment->booking_id)->get();

            // foreach($hotelbook as $item){
            //     $room = RoomHotel::find($item->room_id);
            //     $room->room_allow =  $room->room_allow - $item->total_room;
            //     $room->save();
            // }

            $contract_id = HotelRoomBooking::where('booking_id',$payment->booking_id)->first();
            $contract = ContractRate::where('id',$contract_id->contract_id)->first();
            $agent = Vendor::where('user_id',$booking->user_id)->first();
            $vendor = Vendor::where('id',$booking->vendor_id)->first();
            $affiliator = Vendor::where('affiliate',$vendor->affiliate)->where('type_vendor','agent')->first();

            $data = [
                'booking' => $booking, // $book merupakan instance dari model Booking yang sudah Anda dapatkan
                'contract' => $contract,
                'setting' => Setting::first(),
                'agent' => $agent,
                'hotelbook' => $hotelbook,
                'affiliator'=> $affiliator

            ];

            if (env('APP_ENV') == 'production') {
                Mail::to($booking->vendor->email_reservation)->send(new BookingConfirmationHotel($data));
                Mail::to($booking->vendor->email)->send(new BookingConfirmationHotel($data));
                Mail::to($booking->users->email)->send(new BookingConfirmation($data));
            }
        return redirect()->back()->with('message', 'Email send to agent and hotel');
    }

    public function confirmationcancel($id)
    {
        $payment = PaymentGetwayTransaction::find($id);
        $payment->status = 400;
        $payment->save();

        $booking = Booking::find($payment->booking_id);
        $booking->booking_status = 'invalid payment';
        $booking->save();

        $tranportbookings = OrderTransport::where('booking_id',$payment->booking_id)->first();
        if($tranportbookings){
            $tranportbookings->booking_status = 'invalid payment';
            $tranportbookings->is_see = 0;
            $tranportbookings->save();

        }

        return redirect()->back()->with('message', 'Email send to agent and hotel');
    }

    public function sendconfirmationtoagent($id)
    {
        $payment = PaymentGetwayTransaction::find($id);
        $payment->status = 200;
        $payment->save();

        $booking = Booking::find($payment->booking_id);
        $booking->booking_status = 'paid';
        $booking->save();

        $hotelbook = HotelRoomBooking::where('booking_id',$payment->booking_id)->get();

            // foreach($hotelbook as $item){
            //     $room = RoomHotel::find($item->room_id);
            //     $room->room_allow =  $room->room_allow - $item->total_room;
            //     $room->save();
            // }

            $contract_id = HotelRoomBooking::where('booking_id',$payment->booking_id)->first();
            $contract = ContractRate::where('id',$contract_id->contract_id)->first();
            $agent = Vendor::where('user_id',$booking->user_id)->first();

            $data = [
                'booking' => $booking, // $book merupakan instance dari model Booking yang sudah Anda dapatkan
                'contract' => $contract,
                'setting' => Setting::first(),
                'agent' =>$agent,
                'hotelbook' => $hotelbook
            ];
        // email tes agent
        // Mail::to('softhouse3@themulia.com')->send(new BookingConfirmation($data));
        if (env('APP_ENV') == 'production') {
            Mail::to($booking->users->email)->send(new BookingConfirmation($data));
        }
        return redirect()->back()->with('message', 'Email send to agent and hotel');
    }

    public function sendconfirmationtohotel($id)
    {
        $payment = PaymentGetwayTransaction::find($id);
        $payment->status = 200;
        $payment->save();

        $booking = Booking::find($payment->booking_id);
        $booking->booking_status = 'paid';
        $booking->save();

        $hotelbook = HotelRoomBooking::where('booking_id',$payment->booking_id)->get();

            // foreach($hotelbook as $item){
            //     $room = RoomHotel::find($item->room_id);
            //     $room->room_allow =  $room->room_allow - $item->total_room;
            //     $room->save();
            // }

            $contract_id = HotelRoomBooking::where('booking_id',$payment->booking_id)->first();
            $contract = ContractRate::where('id',$contract_id->contract_id)->first();
            $agent = Vendor::where('user_id',$booking->user_id)->first();
            $vendor = Vendor::where('id',$booking->vendor_id)->first();
            $affiliator = Vendor::where('affiliate',$vendor->affiliate)->where('type_vendor','agent')->first();

            $data = [
                'booking' => $booking, // $book merupakan instance dari model Booking yang sudah Anda dapatkan
                'contract' => $contract,
                'setting' => Setting::first(),
                'agent' => $agent,
                'hotelbook' => $hotelbook,
                'affiliator'=> $affiliator

            ];

        // email tess reservasi
        // Mail::to('softhouse3@themulia.com')->send(new BookingConfirmationHotel($data));
        if (env('APP_ENV') == 'production') {
        Mail::to($booking->vendor->email_reservation)->send(new BookingConfirmationHotel($data));
        Mail::to($booking->vendor->email)->send(new BookingConfirmationHotel($data));
        }
        return redirect()->back()->with('message', 'Email send to agent and hotel');
    }

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
