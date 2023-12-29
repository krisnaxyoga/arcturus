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
        $hotel_select = $request->hotel;

        // $data = HotelRoomBooking::whereHas('booking', function ($query) use ($startdate, $enddate) {
        //     $query->where('booking_status', '<>', '-')
        //           ->where('booking_status', '<>', '');

        //           if ($startdate && $enddate) {
        //             $query->whereBetween('checkout_date', [$startdate, $enddate]);
        //         } elseif ($startdate) {
        //             $query->where('checkout_date', '>=', $startdate);
        //         } elseif ($enddate) {
        //             $query->where('checkout_date', '<=', $enddate);
        //         }
                
        //         // $query->where('checkout_date', '<=', now());

        //         $query->where('booking_status', 'paid');
        // })->whereHas('booking.vendor', function ($query) use ($hotel_select) {
        //     $query->where('vendor_name', $hotel_select);
        // })
        // ->get();

        $data = Booking::where('booking_status', '<>', '-')
            ->where('booking_status', '<>', '');

        if ($startdate && $enddate) {
            $data->whereBetween('checkout_date', [$startdate, $enddate]);
        } elseif ($startdate) {
            $data->where('checkout_date', '>=', $startdate);
        } elseif ($enddate) {
            $data->where('checkout_date', '<=', $enddate);
        }

        $data->where('booking_status', 'paid');

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }

        $data = $data->get();

        $hotels = Booking::where('booking_status', 'paid')
        ->join('vendors', 'bookings.vendor_id', '=', 'vendors.id') // Gabungkan tabel vendor
        ->select('vendors.vendor_name')
        ->groupBy('vendors.vendor_name') // Kelompokkan hasil berdasarkan vendor_name
        ->get();

        return view('admin.report.index',compact('setting','data','startdate','enddate','hotels','hotel_select'));
    }

    public function madeon(Request $request)
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
        $hotel_select = $request->hotel;

        // $data = HotelRoomBooking::whereHas('booking', function ($query) use ($startdate, $enddate) {
        //     $query->where('booking_status', '<>', '-')
        //           ->where('booking_status', '<>', '');

        //           if ($startdate && $enddate) {
        //             $query->whereBetween('checkout_date', [$startdate, $enddate]);
        //         } elseif ($startdate) {
        //             $query->where('checkout_date', '>=', $startdate);
        //         } elseif ($enddate) {
        //             $query->where('checkout_date', '<=', $enddate);
        //         }
                
        //         // $query->where('checkout_date', '<=', now());

        //         $query->where('booking_status', 'paid');
        // })->whereHas('booking.vendor', function ($query) use ($hotel_select) {
        //     $query->where('vendor_name', $hotel_select);
        // })
        // ->get();

        $data = Booking::where('booking_status', '<>', '-')
            ->where('booking_status', '<>', '');

        if ($startdate && $enddate) {
            $data->whereBetween('booking_date', [$startdate, $enddate]);
        } elseif ($startdate) {
            $data->where('booking_date', '>=', $startdate);
        } elseif ($enddate) {
            $data->where('booking_date', '<=', $enddate);
        }

        $data->where('booking_status', 'paid');

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }

        $data = $data->get();

        $hotels = Booking::where('booking_status', 'paid')
        ->join('vendors', 'bookings.vendor_id', '=', 'vendors.id') // Gabungkan tabel vendor
        ->select('vendors.vendor_name')
        ->groupBy('vendors.vendor_name') // Kelompokkan hasil berdasarkan vendor_name
        ->get();

        return view('admin.report.index',compact('setting','data','startdate','enddate','hotels','hotel_select'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function madeonpdfreport(Request $request)
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $settings = Setting::first();
        } else {
            $settings = new Setting;
        }

        $startdate = $request->star_tdate;
        $enddate = $request->end_date;
        $hotel_select = $request->hotelselect;

        // $data = HotelRoomBooking::whereHas('booking', function ($query) use ($startdate, $enddate) {
        //     $query->where('booking_status', '<>', '-')
        //           ->where('booking_status', '<>', '');

        //           if ($startdate && $enddate) {
        //             $query->whereBetween('checkout_date', [$startdate, $enddate]);
        //         } elseif ($startdate) {
        //             $query->where('checkout_date', '>=', $startdate);
        //         } elseif ($enddate) {
        //             $query->where('checkout_date', '<=', $enddate);
        //         }
                
        //         // $query->where('checkout_date', '<=', now());

        //         $query->where('booking_status', 'paid');
        // })->whereHas('booking.vendor', function ($query) use ($hotel_select) {
        //     $query->where('vendor_name', $hotel_select);
        // })
        // ->get();
        $data = Booking::where('booking_status', '<>', '-')
        ->where('booking_status', '<>', '');

        if ($startdate && $enddate) {
            $data->whereBetween('checkout_date', [$startdate, $enddate]);
        } elseif ($startdate) {
            $data->where('checkout_date', '>=', $startdate);
        } elseif ($enddate) {
            $data->where('checkout_date', '<=', $enddate);
        }

        $data->where('booking_status', 'paid');

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }

        $data = $data->get();

        return view('admin.report.pdf',compact('data','settings','startdate','enddate'));
    }

    public function adminpdfreport(Request $request)
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $settings = Setting::first();
        } else {
            $settings = new Setting;
        }

        $startdate = $request->star_tdate;
        $enddate = $request->end_date;
        $hotel_select = $request->hotelselect;

        // $data = HotelRoomBooking::whereHas('booking', function ($query) use ($startdate, $enddate) {
        //     $query->where('booking_status', '<>', '-')
        //           ->where('booking_status', '<>', '');

        //           if ($startdate && $enddate) {
        //             $query->whereBetween('checkout_date', [$startdate, $enddate]);
        //         } elseif ($startdate) {
        //             $query->where('checkout_date', '>=', $startdate);
        //         } elseif ($enddate) {
        //             $query->where('checkout_date', '<=', $enddate);
        //         }
                
        //         // $query->where('checkout_date', '<=', now());

        //         $query->where('booking_status', 'paid');
        // })->whereHas('booking.vendor', function ($query) use ($hotel_select) {
        //     $query->where('vendor_name', $hotel_select);
        // })
        // ->get();
        $data = Booking::where('booking_status', '<>', '-')
        ->where('booking_status', '<>', '');

        if ($startdate && $enddate) {
            $data->whereBetween('checkout_date', [$startdate, $enddate]);
        } elseif ($startdate) {
            $data->where('checkout_date', '>=', $startdate);
        } elseif ($enddate) {
            $data->where('checkout_date', '<=', $enddate);
        }

        $data->where('booking_status', 'paid');

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }

        $data = $data->get();

        return view('admin.report.pdf',compact('data','settings','startdate','enddate'));
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
