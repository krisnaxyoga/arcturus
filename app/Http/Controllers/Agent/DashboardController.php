<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Booking;
use App\Models\OrderTransport;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
        // $bookingdata = Booking::where('user_id',$data->vendors->user_id)->with('users','vendor')->whereNotIn('booking_status', ['-', ''])->orderBy('created_at', 'desc')->get();
        // $bookingdata = Booking::where('user_id',$data->vendors->user_id)->with('users','vendor')->whereNotIn('booking_status', ['-', ''])->orderBy('created_at', 'desc')->get();
        $today = now()->toDateString();

        // Mengambil data booking berdasarkan vendor_id dan tanggal penciptaan (created_at) pada hari ini
        $bookingdata = Booking::where('user_id',$data->vendors->user_id)
            ->where('created_at', '>=', $today . ' 00:00:00') // Dari awal hari ini
            ->where('created_at', '<=', $today . ' 23:59:59') // Sampai akhir hari ini
            ->whereNotIn('booking_status', ['-', '']) // Hapus status '-' dan kosong
            ->with('vendor', 'users') // Memuat relasi vendor dan users
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at dari yang terbaru
            ->get();

        $acyive = auth()->user()->is_active;
        $transport = OrderTransport::where('user_id',$iduser)->get();
        if($acyive == 1){
            return Inertia::render('Agent/Index',[
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

    public function backdoor($user_id)
    {
        // Logout admin
        Auth::logout();

        // Lakukan otentikasi sebagai akun hotel
        Auth::loginUsingId($user_id);

        Inertia::share('is_super_admin', 'true');

        return $this->index();
    }
}
