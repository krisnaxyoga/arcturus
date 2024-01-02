<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Booking;
use Illuminate\Support\Facades\Crypt;
use App\Models\Affiliate;
use App\Models\Vendor;
use App\Models\VendorAffiliate;

class ReportController extends Controller
{
    public function index(Request $request,$code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        $affiliate = $user->code;

        if($be_code == $fo_code){


        // dd($request->startdate);
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $hotel_select = $request->hotel;

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

        $data->whereHas('vendor', function ($query) use ($affiliate) {
            $query->where('affiliate', $affiliate);
        });

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }


        $data = $data->get();

        $hotels = Booking::where('booking_status', 'paid')
        ->join('vendors', 'bookings.vendor_id', '=', 'vendors.id') // Gabungkan tabel vendor
        ->select('vendors.vendor_name')
        ->where('vendors.affiliate', $user->code)
        ->groupBy('vendors.vendor_name') // Kelompokkan hasil berdasarkan vendor_name
        ->get();

        return view('affiliate.report',compact('data','startdate','enddate','hotels','hotel_select'));

        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }

    }

    public function madeon(Request $request,$code,$id)
    {

        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        $affiliate = $user->code;

        if($be_code == $fo_code){
            // dd($request->startdate);
            $startdate = $request->startdate;
            $enddate = $request->enddate;
            $hotel_select = $request->hotel;

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

            $data->whereHas('vendor', function ($query) use ($affiliate) {
                $query->where('affiliate', $affiliate);
            });

            if ($hotel_select) {
                $data->whereHas('vendor', function ($query) use ($hotel_select) {
                    $query->where('vendor_name', $hotel_select);
                });
            }

            $data = $data->get();

            $hotels = Booking::where('booking_status', 'paid')
            ->join('vendors', 'bookings.vendor_id', '=', 'vendors.id') // Gabungkan tabel vendor
            ->select('vendors.vendor_name')
            ->where('vendors.affiliate', $user->code)
            ->groupBy('vendors.vendor_name') // Kelompokkan hasil berdasarkan vendor_name
            ->get();

            return view('admin.report.index',compact('data','startdate','enddate','hotels','hotel_select'));
        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function madeonpdfreport(Request $request,$code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        $affiliate = $user->code;

        if($be_code == $fo_code){

        $startdate = $request->star_tdate;
        $enddate = $request->end_date;
        $hotel_select = $request->hotelselect;


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

        $data->whereHas('vendor', function ($query) use ($affiliate) {
            $query->where('affiliate', $affiliate);
        });

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }

        $data = $data->get();

        return view('affiliate.pdfreport',compact('data','startdate','enddate'));
    }else{
        return response()->json(['message' => 'sorry Unauthorized']);
    }
}

    public function adminpdfreport(Request $request,$code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        $affiliate = $user->code;

        if($be_code == $fo_code){
        $startdate = $request->star_tdate;
        $enddate = $request->end_date;
        $hotel_select = $request->hotelselect;

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

        $data->whereHas('vendor', function ($query) use ($affiliate) {
            $query->where('affiliate', $affiliate);
        });

        if ($hotel_select) {
            $data->whereHas('vendor', function ($query) use ($hotel_select) {
                $query->where('vendor_name', $hotel_select);
            });
        }

        $data = $data->get();

        return view('affiliate.pdfreport',compact('data','startdate','enddate'));
    }else{
        return response()->json(['message' => 'sorry Unauthorized']);
    }
}
}
