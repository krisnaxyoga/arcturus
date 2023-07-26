<?php

namespace App\Http\Controllers\Vendor;

use App\Models\User;
use App\Models\Booking;
use App\Models\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return inertia('Vendor/Index');
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->first();
        $totalincome = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->sum('price');
        $totalbooking = Booking::where('vendor_id',$vendor->id)->count();
        $bookingsuccess = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->count();
        $pendingpayment = Booking::where('vendor_id',$vendor->id)->where('booking_status','unpaid')->count();
        $booking = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor','users')->get();
        $acyive = auth()->user()->is_active;
        if($acyive == 1){
            return inertia('Vendor/Index',[
                'income'=>$totalincome,
                'booking'=>$totalbooking,
                'success'=>$bookingsuccess,
                'pending'=>$pendingpayment,
                'data'=>$booking
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
