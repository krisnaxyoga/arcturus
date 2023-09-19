<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $data = Vendor::where('type_vendor','hotel')->get();

        return view('admin.hotel.index',compact('data','setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $model = new Vendor;
        $user = new User;
        $countries = get_country_lists();

        return view('admin.hotel.form',compact('model', 'user', 'countries','setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                // $gambarPaths = [];

                // foreach ($request->file('gambar') as $gambar) {
                //     $filename = uniqid().'.'.$gambar->getClientOriginalExtension();
                //     $gambar->move(public_path('hotel'), $filename);

                //     $gambarPaths[] = "/hotel/".$filename;
                // }
                // dd($gambarPaths);
                $data = new User();
                $data->first_name = $request->firstname;
                $data->last_name = $request->lastname;
                $data->email = $request->email;
                $data->password = Hash::make('password123');
                $data->role_id = 2;
                $data->save();
    
                $vendor = new Vendor();
                $vendor->user_id = $data->id;
                $vendor->vendor_name = $request->vendor_name;
                $vendor->vendor_legal_name = $request->vendor_legal_name;
                $vendor->logo_img = '-';
                $vendor->type_vendor = 'hotel';
                $vendor->address_line1 = $request->address1;
                $vendor->address_line2 = $request->address2;
                $vendor->phone = $request->phone;
                $vendor->email = $request->email;
                $vendor->country = $request->country;
                $vendor->state = $request->state;
                $vendor->city = $request->city;
                $vendor->system_markup = $request->markup;
                $vendor->save();
    
                //get vendor by user_id 
                $vendor = Vendor::where('user_id',$data->id)->first();
                // update vendor_id on user table
                $user = User::find($vendor->user_id);;
                $user->vendor_id = $vendor->id;
                $user->update();

                return redirect()
                ->route('dashboard.hotel')
                ->with('message', 'Data berhasil disimpan.');
            }
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
    public function edit(string $id)
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }
        
        $model = Vendor::find($id);
        $countries = get_country_lists();
 
        return view('admin.hotel.form',compact('model','countries','setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $country = get_country_lists();
        //dd($request);
        $validator =  Validator::make($request->all(), [
            'vendor_name' => 'required',
            'email' => 'required'
        ]);

        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        //update vendor
        $vendor = Vendor::find($id);
        $vendor->vendor_name = $request->vendor_name;
        $vendor->vendor_legal_name = $request->vendor_legal_name;
        $vendor->address_line1 = $request->address1;
        $vendor->address_line2 = $request->address2;
        $vendor->phone = $request->phone;
        $vendor->email = $request->email;
        $vendor->country = $request->country;
        $vendor->state = $request->state;
        $vendor->city = $request->city;
        $vendor->system_markup = $request->markup;
        $vendor->update();
        
        // update user
        $user = User::find($vendor->user_id);;
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->update();
        
        return redirect()
            ->route('dashboard.hotel.edit', ['id' => $id])
            ->with('message', 'Data Hotel berhasil diupdate.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hotel = Vendor::findOrFail($id);
        $user = User::findOrFail($hotel->user_id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $hotel->delete();
        $user->delete();

        return redirect()
            ->route('dashboard.hotel')
            ->with('message', 'Data Hotel berhasil dihapus.');
    }

    public function loginhotel($id){
        if (Auth::check() && Auth::user()->role_id == 1) {
            // Logout admin
            Auth::logout();

            // Lakukan otentikasi sebagai akun hotel
            Auth::loginUsingId($id);

            // Redirect ke halaman hotel
            return redirect('/vendordashboard');
        }
    }
}
