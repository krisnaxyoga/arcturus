<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use App\Models\Vendor;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Mail\ForgotPassword;
use App\Mail\RegisterNotif;
use App\Mail\AgentVerifification;
use App\Mail\HotelVerifification;

class AuthController extends Controller
{
    public function login(Request $request) {
        return view('auth.login');
    }

    public function dologin(Request $request) {
        // validasi
        //dd($request);
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
// dd($credentials);
        if (auth()->attempt($credentials)) {

            // buat ulang session login
            $request->session()->regenerate();


            if (auth()->user()->role_id === 1) {
                // jika user superadmin
                return redirect()->intended('/admin');
            } else if (auth()->user()->role_id === 2) {
                // jika user vendordashboard
                return redirect()->intended('/vendordashboard');
            }else{
                return redirect()->intended('/agentdashboard');
            }
        }
        // jika email atau password salah
        // kirimkan session error
        return back()->with('error', 'email or password wrong');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function registeragent(Request $request){
        return view('auth.registeragent');
    }

    public function registvendor(Request $request){
        return view('auth.registervendor');
    }

    public function agentstore(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            // add new users
            $data = new User();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->role_id = 3;
            $data->departement = '-';
            $data->position = '-';
            $data->save();

            // add new agents
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
            $member->save();

            $Setting = Setting::where('id',1)->first();
            Mail::to($Setting->email)->send(new RegisterNotif($data, $member));
            
            Mail::to($request->email)->send(new AgentVerifification($data, $member));

            // update vendor_id in tabel users where id = $data->id
            $user = User::find($data->id);
            $user->vendor_id = $member->id;
            $user->save();

            return redirect()
                ->route('login')
                ->with('message', 'please check your email to activate your account.');
                // ->with('message', 'Please wait max 1x24 hours for ADMIN to verify your account');
        }
    }

    public function vendorstore(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data = new User();
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $request->email;
            $data->mobile_phone = $request->phone;
            $data->password = Hash::make($request->password);
            $data->departement = '-';
            $data->position = 'master';
            $data->title = Str::random(8);
            $data->role_id = 2;
            $data->save();

            $member = new Vendor();
            $member->user_id = $data->id;
            $member->vendor_name = $request->busisnes_name;
            $member->vendor_legal_name = $request->company_name;
            $member->address_line1 = $request->address;
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->type_vendor = $request->type_vendor;
            $member->is_active = 0;
            $member->save();

            $user = User::find($data->id);
            $user->vendor_id = $member->id;
            $user->save();
            // dd($member->id);

            $Setting = Setting::where('id',1)->first();
            Mail::to($Setting->email)->send(new RegisterNotif($data, $member));
            Mail::to($request->email)->send(new HotelVerifification($data, $member));
            
            return redirect()
                ->route('login')
                ->with('message', 'please check your email to activate your account.');
        }
    }
    public function forgetpassword(){
        return view('auth.forgetpassword');
    }

    public function sendEmail(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        // Generate a new password reset token
        $token = Str::random(8);

        // Save the token to the database
        $user->password = Hash::make($token);
        $user->save();

        // Send an email to the user with the password reset link
        Mail::to($user->email)->send(new ForgotPassword($user, $token));

        // Return a success response
        return redirect()
                ->route('login')
                ->with('message', 'The password has been updated successfully, you can check your email for a new password');
    }

    public function verifaccount($id){

        $data = User::find($id);
        $data->is_active = 1;
        $data->save();

        $vendor = Vendor::where('user_id',$data->id)->first();
        $vendor->is_active = 1;
        $vendor->save();

        return redirect()
                ->route('login')
                ->with('message', 'Thank you, your account has now been verified, please login here.');
    }
}
