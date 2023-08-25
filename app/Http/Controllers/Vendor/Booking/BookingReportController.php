<?php

namespace App\Http\Controllers\Vendor\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Booking;

class BookingReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = auth()->user()->id;
        $vendor = Vendor::where('user_id',$userid)->with('users')->first();
        $data = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor')->with('users')->get();
        return inertia('Vendor/BookingReport/Index',[
            'data' => $data,
            'vendor' => $vendor
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
