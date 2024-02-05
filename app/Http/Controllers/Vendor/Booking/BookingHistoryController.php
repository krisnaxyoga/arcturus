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

        foreach($hotelroombooking as $item){
            // $room = RoomHotel::where('id',$item->room_id)->first();
            if($item->room_name == null){
                $item->room_name = $item->room->ratedesc ?? 'unknow';
                $item->contract_name = $item->contractrate->codedesc ?? '<p>unknow</p>';
                $item->benefit_policy = $item->contractrate->benefit_policy ?? '<p>unknow</p>';
                $item->other_policy = $item->contractrate->other_policy ?? '<p>unknow</p>';
                $item->cencellation_policy = $item->contractrate->cencellation_policy ?? '<p>unknow</p>';
                $item->deposit_policy = $item->contractrate->deposit_policy ?? '<p>unknow</p>';
                $item->save();
            }
        }

        $cont_id = HotelRoomBooking::where('booking_id',$id)->first();
        $conttract = ContractRate::where('id',$cont_id->contract_id)->first();
        $setting = Setting::first();
        $affiliator = Vendor::where('affiliate',$vendor->affiliate)->where('type_vendor','agent')->first();

        return inertia('Vendor/BookingHistory/Detail',[
            'data' => $data,
            'roombooking'=>$hotelroombooking,
            'contract' => $conttract,
            'vendor' => $vendor,
            'setting' => $setting,
            'affiliator' => $affiliator
        ]);
    }
}
