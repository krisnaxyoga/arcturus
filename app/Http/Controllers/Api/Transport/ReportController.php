<?php

namespace App\Http\Controllers\Api\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderTransport;
use App\Http\Resources\PostResource;
use App\Models\WidrawTransport;

class ReportController extends Controller
{
    public function index(){
        $iduser = auth()->guard('agent_transport')->id();
        $data = OrderTransport::where('transport_id',$iduser)->with([
            'booking' => function ($query) {
                // Pilih kolom-kolom yang Anda inginkan dari relasi booking
                $query->select('id', 'vendor_id', /* tambahkan kolom lain jika diperlukan */);
            },
            'booking.vendor' => function ($query) {
                // Pilih kolom-kolom yang Anda inginkan dari relasi vendor
                $query->select('id', 'vendor_name', /* tambahkan kolom lain jika diperlukan */);
            }
        ])->orderBy('created_at','desc')->get();

        return new PostResource(true, 'List Data report', $data);
    }

    public function detail($id){

        $data = OrderTransport::where('id',$id)->with([
            'booking' => function ($query) {
                // Pilih kolom-kolom yang Anda inginkan dari relasi booking
                $query->select('id', 'vendor_id', /* tambahkan kolom lain jika diperlukan */);
            },
            'booking.vendor' => function ($query) {
                // Pilih kolom-kolom yang Anda inginkan dari relasi vendor
                $query->select('id', 'vendor_name', /* tambahkan kolom lain jika diperlukan */);
            }
        ])->first();

        return new PostResource(true, 'Detail Data report', $data);
    }

    public function widraw($id){

        $data = WidrawTransport::where('ordertransport_id',$id)->first();

        return new PostResource(true, 'Detail Data report', $data);
    }
}
