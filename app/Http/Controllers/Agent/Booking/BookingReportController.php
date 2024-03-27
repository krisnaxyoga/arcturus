<?php

namespace App\Http\Controllers\Agent\Booking;

use App\Models\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\OrderTransport;

class BookingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $agent = User::where('id',$iduser)->with('vendors')->first();
        // $booking = Booking::with('users','vendor')->where('user_id',$agent->vendors->user_id)->get();
        $booking = Booking::with('users','vendor')->whereNotIn('booking_status', ['-', ''])->where('user_id',$agent->vendors->user_id)->orderBy('created_at', 'desc')->get();
        $ordertranport = OrderTransport::where('user_id',$iduser)->get();
        $transport = OrderTransport::where('user_id',$iduser)->get();
        return inertia('Agent/BookingReport/Index',[
            'data' => $booking,
            'agent' => $agent,
            'ordertransport' => $ordertranport,
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
