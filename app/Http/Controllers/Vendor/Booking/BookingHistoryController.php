<?php

namespace App\Http\Controllers\Vendor\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Booking;
use App\Models\ContractRate;
use App\Models\HotelRoomBooking;
use App\Models\Setting;

use App\Models\Affiliate;

class BookingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = auth()->user()->id;
        $vendor = Vendor::where('user_id',$userid)->with('users')->first();
        $data = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor')->with('users')->orderBy('created_at', 'desc')->get();


        return inertia('Vendor/BookingHistory/Index',[
            'data'=>$data,
            'vendor'=>$vendor
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userid = auth()->user()->id;
        $vendor = Vendor::where('user_id',$userid)->with('users')->first();
        $data = Booking::where('id',$id)->whereNotIn('booking_status', ['-', ''])
        ->with([
            'users' => function ($query) {
                // Pilih kolom-kolom yang Anda inginkan dari relasi booking
                $query->select('*');
            },
            'users.vendors' => function ($query) {
                // Pilih kolom-kolom yang Anda inginkan dari relasi vendor
                $query->select('*');
            }
        ])
        ->with('vendor')->first();
        $hotelroombooking = HotelRoomBooking::where('booking_id',$id)->with('room')->with('contractprice')->with('contractrate')->get();
        $cont_id = HotelRoomBooking::where('booking_id',$id)->first();
        $conttract = ContractRate::where('id',$cont_id->contract_id)->first();
        $setting = Setting::first();
        $affiliator = Affiliate::where('code',$vendor->affiliate)->first();

        return inertia('Vendor/BookingHistory/Detail',[
            'data'=>$data,
            'roombooking'=>$hotelroombooking,
            'contract' => $conttract,
            'vendor' =>$vendor,
            'setting' => $setting,
            'affiliator' => $affiliator
        ]);
    }
}
