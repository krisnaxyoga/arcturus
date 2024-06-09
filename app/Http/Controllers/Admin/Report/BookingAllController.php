<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Booking;
use App\Models\PaymentGetwayTransaction;

use App\Models\RoomHotel;
use App\Models\Vendor;
use App\Models\User;
use App\Models\ContractRate;
use App\Models\HotelRoomBooking;
use App\Mail\BookingConfirmation;
use App\Mail\BookingConfirmationHotel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BookingAllController extends Controller
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

        $today = now()->toDateString();

        $data = Booking::with('users','vendor')
        ->where('created_at', '>=', $today . ' 00:00:00') // Dari awal hari ini
        ->where('created_at', '<=', $today . ' 23:59:59') // Sampai akhir hari ini
        ->whereNotIn('booking_status', ['-', '','unpaid','cancelled'])->orderBy('created_at', 'desc')->get();
        // dd($data);

        $isee = Booking::where('is_see',0)->get();
        foreach($isee as $show){
            $show->is_see = 1;
            $show->save();
        }

        return view('admin.booking.allbooking',compact('setting','data'));
    }

    public function confirmation($id)
    {

        $booking = Booking::find($id);
        $booking->booking_status = 'paid';
        $booking->save();

        $hotelbook = HotelRoomBooking::where('booking_id',$id)->get();

            // foreach($hotelbook as $item){
            //     $room = RoomHotel::find($item->room_id);
            //     $room->room_allow =  $room->room_allow - $item->total_room;
            //     $room->save();
            // }

            $contract_id = HotelRoomBooking::where('booking_id',$id)->first();
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

            // // Mail::to($booking->vendor->email_reservation)->send(new BookingConfirmationHotel($data));
            // Mail::to('yogakris.yk@gmail.com')->send(new BookingConfirmationHotel($data));
            // Mail::to('oxygenjuno@gmail.com')->send(new BookingConfirmation($data));

            if (env('APP_ENV') == 'production') {
                Mail::to($booking->vendor->email_reservation)->send(new BookingConfirmationHotel($data));
                Mail::to($booking->vendor->email)->send(new BookingConfirmationHotel($data));
                Mail::to($booking->users->email)->send(new BookingConfirmation($data));
            }
        return redirect()->back()->with('message', 'Email send to agent and hotel');
    }

    public function sendconfirmationtoagent($id)
    {

        $booking = Booking::find($id);
        $booking->booking_status = 'paid';
        $booking->save();

        $hotelbook = HotelRoomBooking::where('booking_id',$id)->get();

            // foreach($hotelbook as $item){
            //     $room = RoomHotel::find($item->room_id);
            //     $room->room_allow =  $room->room_allow - $item->total_room;
            //     $room->save();
            // }

            $contract_id = HotelRoomBooking::where('booking_id',$id)->first();
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
        // Mail::to('oxygenjuno@gmail.com')->send(new BookingConfirmation($data));
        if (env('APP_ENV') == 'production') {
        Mail::to($booking->users->email)->send(new BookingConfirmation($data));
        }

        return redirect()->back()->with('message', 'Email send to agent');
    }

    public function sendconfirmationtohotel($id)
    {
        // $payment = PaymentGetwayTransaction::find($id);
        // $payment->status = 200;
        // $payment->save();

        $booking = Booking::find($id);
        $booking->booking_status = 'paid';
        $booking->save();

        $hotelbook = HotelRoomBooking::where('booking_id',$id)->get();

            // foreach($hotelbook as $item){
            //     $room = RoomHotel::find($item->room_id);
            //     $room->room_allow =  $room->room_allow - $item->total_room;
            //     $room->save();
            // }

            $contract_id = HotelRoomBooking::where('booking_id',$id)->first();
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
        // Mail::to('yogakris.yk@gmail.com')->send(new BookingConfirmationHotel($data));
        if (env('APP_ENV') == 'production') {
            Mail::to($booking->vendor->email_reservation)->send(new BookingConfirmationHotel($data));
            Mail::to($booking->vendor->email)->send(new BookingConfirmationHotel($data));
        }
        return redirect()->back()->with('message', 'Email sent to hotel');

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
