<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Booking;
use App\Models\HotelRoomBooking;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }
        // dd($request->startdate);
        $startdate = $request->startdate;
        $enddate = $request->enddate;

        $data = HotelRoomBooking::whereHas('booking', function ($query) use ($startdate, $enddate) {
            $query->where('booking_status', '<>', '-')
                  ->where('booking_status', '<>', '');

                  if ($startdate && $enddate) {
                    $query->whereBetween('booking_date', [$startdate, $enddate]);
                } elseif ($startdate) {
                    $query->where('booking_date', '>=', $startdate);
                } elseif ($enddate) {
                    $query->where('booking_date', '<=', $enddate);
                }
                
                // $query->where('checkout_date', '<=', now());

                $query->where('booking_status', 'paid');
        })
        ->get();

        return view('admin.report.index',compact('setting','data','startdate','enddate'));
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
