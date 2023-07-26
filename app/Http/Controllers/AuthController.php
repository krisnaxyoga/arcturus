<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;
use App\Models\Vendor;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
            $data->save();

            // add new agents
            $member = new Vendor();
            $member->user_id = $data->id;
            $member->vendor_name = $request->busisnes_name;
            $member->address_line1 = $request->address;
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->type_vendor = 'agent';
            $member->is_active = 1;
            $member->save();

            // update vendor_id in tabel users where id = $data->id
            $user = User::find($data->id);
            $user->vendor_id = $member->id;
            $user->save();

            return redirect()
                ->route('login')
                ->with('message', 'register success');;
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
            $data->mobile_phone = $request->mobile_phone;
            $data->password = Hash::make($request->password);
            $data->departement = '-';
            $data->position = '-';
            $data->role_id = 2;
            $data->save();

            $member = new Vendor();
            $member->user_id = $data->id;
            $member->vendor_name = $request->busisnes_name;
            $member->address_line1 = $request->address;
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->type_vendor = $request->type_vendor;
            $member->is_active = 1;
            $member->save();

            // dd($member->id);
            return redirect()
                ->route('login')
                ->with('message', 'register success');
        }
    }
}
