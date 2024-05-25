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
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
        // $booking = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor','users')->orderBy('created_at', 'desc')->get();
        // Mendapatkan tanggal hari ini
        $today = now()->toDateString();

        // Mengambil data booking berdasarkan vendor_id dan tanggal penciptaan (created_at) pada hari ini
        $booking = Booking::where('vendor_id', $vendor->id)
            ->where('created_at', '>=', $today . ' 00:00:00') // Dari awal hari ini
            ->where('created_at', '<=', $today . ' 23:59:59') // Sampai akhir hari ini
            ->whereNotIn('booking_status', ['-', '']) // Hapus status '-' dan kosong
            ->with('vendor', 'users') // Memuat relasi vendor dan users
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at dari yang terbaru
            ->get();
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
        } else {
            return view('landingpage.pagenotfound.isactiveaccount');
        }
    }

    public function backdoor($user_id)
    {
        // Logout admin
        Auth::logout();

        // Lakukan otentikasi sebagai akun hotel
        Auth::loginUsingId($user_id);

        Inertia::share('position', 'master');
        Inertia::share('is_super_admin', 'true');

        return $this->index();
    }
}
