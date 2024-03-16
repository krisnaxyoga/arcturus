<?php

namespace App\Http\Controllers\Api\Transport;

use App\Http\Controllers\Controller;

use App\Models\AgentTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\TransportBankAccount;
use App\Http\Resources\PostResource;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        // If auth success
        $token = auth()->guard('agent_transport')->login($user);
        
        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $token
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function user()
    {
        //get posts
        $posts = User::latest()->paginate(5);

        //return collection of posts as a resource
        return new PostResource(true, 'List Data User', $posts);
        
    }

    public function userlogin($id)
    {
        //get posts
        $posts = AgentTransport::select('id', 'address', 'company_name', 'created_at', 'email', 'markup', 'mobile_phone', 'status', 'updated_at')
        ->where('id', $id)
        ->first();    

        //return collection of posts as a resource
        return new PostResource(true, 'User Login', $posts);
        
    }

    public function logout(Request $request)
    {
         //remove token
         $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

         if($removeToken) {
             //return response JSON
             return response()->json([
                 'success' => true,
                 'message' => 'Logout Berhasil!',
             ]);
         }
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
  

    /**
     * Update the specified resource in storage.
     */
    public function profileupdate(Request $request, string $id)
    {

         // Set validation rules
         $validator = Validator::make($request->all(), [
            'email'     => 'nullable'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $user = AgentTransport::where('id', $id)->first(); 

        if(!$request->password){
            $user->company_name = $request->company_name ?? $user->company_name;
            $user->email = $request->email ?? $user->email;
            $user->mobile_phone = $request->mobile_phone ?? $user->mobile_phone;
            $user->address = $request->address ?? $user->address;
        }else{
            $user->company_name = $request->company_name ?? $user->company_name;
            $user->email = $request->email ?? $user->email;
            $user->mobile_phone = $request->mobile_phone ?? $user->mobile_phone;
            $user->address = $request->address ?? $user->address;
            $user->password = Hash::make($request->password);
            $user->code = $request->password;
        }

        $user->save();

        return new PostResource(true, 'profile update', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bankaccountlist(string $id)
    {
        $data = TransportBankAccount::where('transport_id',$id)->first();
        return new PostResource(true, 'data Saved!', $data);
    }

    public function bankaccount(Request $request,$id)
    {
         // Set validation rules
         $validator = Validator::make($request->all(), [
            'bank_address'     => 'nullable'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = TransportBankAccount::where('transport_id',$id)->first();

        if(!$data){
            $data = new TransportBankAccount;
        }

        $data->transport_id = $id;
        $data->bank_address = $request->bank_address;
        $data->account_number = $request->account_number;
        $data->bank_name = $request->bank_name;
        $data->swif_code = $request->swif_code;
        $data->save();

        return new PostResource(true, 'data Saved!', $data);

    } 
}
