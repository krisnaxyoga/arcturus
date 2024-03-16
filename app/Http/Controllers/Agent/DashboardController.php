<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Booking;
use App\Models\OrderTransport;

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

        $totalbooking = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->sum('price');
        $bookingsuccess = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->count();
        $pendingpayment = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','unpaid')->count();
        $roomhotel1 = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->get();
        $roomhotel = 0;
        foreach($roomhotel1 as $item){
            $roomhotel += $item->night * $item->total_room;
        }
        $bookingdata = Booking::where('user_id',$data->vendors->user_id)->with('users','vendor')->whereNotIn('booking_status', ['-', ''])->orderBy('created_at', 'desc')->get();
        $acyive = auth()->user()->is_active;
        $transport = OrderTransport::where('user_id',$iduser)->get();
        if($acyive == 1){
            return inertia('Agent/Index',[
                'data' => $data,
                'booking' => $totalbooking,
                'success' => $bookingsuccess,
                'pending' => $pendingpayment,
                'getbooking' => $bookingdata,
                'totalroom' => $roomhotel,
                'transport' => $transport
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
}
