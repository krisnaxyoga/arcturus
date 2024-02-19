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

use App\Models\Role;
use App\Models\Setting;
use App\Models\VendorAffiliate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Models\Affiliate;
use App\Mail\ForgotPassword;
use App\Mail\RegisterNotif;
use App\Mail\AgentVerifification;
use App\Mail\HotelVerifification;


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
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or Password Wrong'
            ], 401);
        }

        // If auth success
        // auth()->login($user);
        
        // If auth success
        $token = auth()->guard('api')->login($user);

        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),
            'token'   => $token
        ], 200);
    }

    public function agentstore(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => 'required',
            'busisnes_name' => 'required',
            'company_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'affiliate' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $data = new User();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->role_id = 3;
            $data->departement = '-';
            $data->position = '-';
            $data->is_see = 0;
            $data->is_active = 0;
            $data->save();

            $member = new Vendor();
            $member->user_id = $data->id;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->vendor_name = $request->busisnes_name;
            $member->vendor_legal_name = $request->company_name;
            $member->address_line1 = $request->address;
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->type_vendor = 'agent';
            $member->is_active = 0;
            if ($request->affiliate) {
                $member->affiliate = $request->affiliate;
            }
            $member->save();

            $setting = Setting::where('id', 1)->first();
            
            $user = User::find($data->id);
            $user->vendor_id = $member->id;
            $user->save();

            if (env('APP_ENV') == 'production') {
                Mail::to($setting->email)->send(new RegisterNotif($data, $member));
                Mail::to($request->email)->send(new AgentVerifification($data, $member));
            }

            return response()->json(['message' => 'Please check your email to activate your account.'], 200);
        }
    }

    public function user()
    {
        //get posts
        $posts = auth()->guard('api')->user()->id;

        //return collection of posts as a resource
        return new PostResource(true, 'List Data User', $posts);
        
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
    
    public function forgotpassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Generate a new password reset token
        $token = Str::random(8);

        // Save the token to the database
        $user->password = Hash::make($token);
        $user->save();

        // Send an email to the user with the password reset link
        if (env('APP_DEBUG') == 'false') {
            Mail::to($user->email)->send(new ForgotPassword($user, $token));
        }

        // Return a success response
        return response()->json(['message' => 'The password has been updated successfully. Check your email for a new password.'], 200);
    }

}
