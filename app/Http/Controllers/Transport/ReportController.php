<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransport;

class ReportController extends Controller
{
    public function index($token,$id){
        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();

        return inertia('Transport/BookingHistory',[
            'token' => $token,
            'user' => $user
        ]);
    }

    public function show($idadata,$token,$id){
        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();

        return inertia('Transport/BookingDetail',[
            'token' => $token,
            'user' => $user,
            'iddata' => $idadata
        ]);
    }
}
