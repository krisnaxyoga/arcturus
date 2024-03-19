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
        $data = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor')->with('users')->orderBy('created_at', 'desc')->get();
        $today = now()->toDateString();

        // Mengambil data booking berdasarkan vendor_id dan tanggal penciptaan (created_at) pada hari ini
        // $data = Booking::where('vendor_id', $vendor->id)
        //     ->where('created_at', '>=', $today . ' 00:00:00') // Dari awal hari ini
        //     ->where('created_at', '<=', $today . ' 23:59:59') // Sampai akhir hari ini
        //     ->whereNotIn('booking_status', ['-', '']) // Hapus status '-' dan kosong
        //     ->with('vendor', 'users') // Memuat relasi vendor dan users
        //     ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at dari yang terbaru
        //     ->get();
        return inertia('Vendor/BookingReport/Index',[
            'data' => $data,
            'vendor' => $vendor
        ]);
    }


}
