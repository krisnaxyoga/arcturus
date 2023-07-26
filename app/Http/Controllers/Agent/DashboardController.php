<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        //$vendor = User::where('id',$iduser)->with('vendors')->first();
        $data = User::where('id',$iduser)->with('vendors')->first();

        $totalbooking = Booking::where('user_id',$data->vendors->user_id)->count();
        $bookingsuccess = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->count();
        $pendingpayment = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','unpaid')->count();

        $bookingdata = Booking::where('user_id',$data->vendors->user_id)->with('users','vendor')->whereNotIn('booking_status', ['-', ''])->get();
        return inertia('Agent/Index',[
            'data' => $data,
            'booking' => $totalbooking,
            'success' => $bookingsuccess,
            'pending' => $pendingpayment,
            'getbooking' => $bookingdata
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
