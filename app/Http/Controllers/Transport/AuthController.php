<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransport;

class AuthController extends Controller
{
    public function index()
    {
        return inertia('Transport/Login');
    }

    public function profile($token,$id){

        $user = AgentTransport::select('id', 'address', 'code', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();
      

        return inertia('Transport/Profile',[
            'token' => $token,
            'user' => $user,
        ]);
    }
}
