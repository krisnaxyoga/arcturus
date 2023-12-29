<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransport;

class HomeController extends Controller
{
    public function index($token,$id){
        
        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();  
        return inertia('Transport/Index',[
            'token' => $token,
            'user' => $user
        ]);
    }
}
