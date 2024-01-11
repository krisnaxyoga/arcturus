<?php

namespace App\Http\Controllers\Vendor;

use App\Models\User;
use App\Models\Booking;
use App\Models\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomHotel;
use App\Models\WidrawVendor;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return inertia('Vendor/Index');
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->with('users')->first();
        $totalincome = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->sum('pricenomarkup');
        $totalbooking = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->count();
        $bookingsuccess = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->count();
        $pendingpayment = Booking::where('vendor_id',$vendor->id)->where('booking_status','unpaid')->count();
        $booking = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor','users')->orderBy('created_at', 'desc')->get();
        $acyive = auth()->user()->is_active;
        $roomhotel1 = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->get();
        $roomhotel = 0;
        foreach($roomhotel1 as $item){
            $roomhotel += $item->night * $item->total_room;
        }

        $widraw = WidrawVendor::where('vendor_id', $vendor->id)
        ->whereDate('created_at', '=', Carbon::today())
        ->get();
        // dd($widraw,$vendor->id,Carbon::today());
        if($acyive == 1){
            return inertia('Vendor/Index',[
                'income'=>$totalincome,
                'booking'=>$totalbooking,
                'success'=>$bookingsuccess,
                'pending'=>$pendingpayment,
                'data'=>$booking,
                'widraw'=>$widraw,
                'totalroom' => $roomhotel,
                'vendor' => $vendor,
            ]);
        }else{
            return view('landingpage.pagenotfound.isactiveaccount');
        }
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

    /**
     * Remove the specified resource from storage.
     */
    public function enquiry(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function verification(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function payouts(string $id)
    {
        //
    }
}
