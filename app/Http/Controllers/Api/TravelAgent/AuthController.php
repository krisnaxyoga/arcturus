<?php

namespace App\Http\Controllers\Api\TravelAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\TransportBankAccount;
use App\Http\Resources\PostResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Set validation rules
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get credentials from request
        $credentials = $request->only('email', 'password');

        // Check email and password
        $user = AgentTransport::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password Wrong'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $token
        ], 200);
    }
}
