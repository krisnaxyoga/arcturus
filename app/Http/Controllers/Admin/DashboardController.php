<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Vendor;
use App\Models\Booking;
use App\Models\RoomHotel;

class DashboardController extends Controller
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

        $totalbooking = Booking::where('booking_status','paid')->count();

        $totaltransaction = Booking::where('booking_status','paid')->sum('price');
        $totalunpaid = Booking::where('booking_status','unpaid')->sum('price');
        $hotel = Vendor::where('type_vendor','hotel')->count();
        $agent = Vendor::where('type_vendor','agent')->count();

        $vendor = Vendor::select('*')->orderBy('created_at', 'desc')->get();

        $paid = Booking::where('booking_status','paid')->count();
        $unpaid = Booking::where('booking_status','unpaid')->count();
        $unknow = Booking::whereNotIn('booking_status', ['paid', 'unpaid'])->count();

        $booking = Booking::whereNotIn('booking_status', ['-', ''])->with('users','vendor')->get();

        $roomhotel = Booking::where('booking_status','paid')->sum('night');
        return view('admin.index',compact('setting','totalbooking','totalunpaid','totaltransaction','hotel','agent','paid','unpaid','unknow','booking','vendor','roomhotel'));
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
