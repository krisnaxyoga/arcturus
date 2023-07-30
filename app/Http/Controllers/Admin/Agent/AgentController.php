<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
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


        $data = Vendor::where('type_vendor','agent')->get();
        return view('admin.agent.index',compact('data','setting'));
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

        return view('admin.agent.form',compact('model', 'user', 'countries', 'setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data = new User();
            $data->first_name = $request->firstname;
            $data->last_name = $request->lastname;
            $data->email = $request->email;
            $data->password = Hash::make('password123');
            $data->role_id = 3;
            $data->save();

            $agent = new Vendor();
            $agent->user_id = $data->id;
            $agent->vendor_name = $request->vendor_name;
            $agent->vendor_legal_name = $request->vendor_legal_name;
            $agent->type_vendor = 'agent';
            $agent->address_line1 = $request->address1;
            $agent->address_line2 = $request->address2;
            $agent->phone = $request->phone;
            $agent->email = $request->email;
            $agent->country = $request->country;
            $agent->state = $request->state;
            $agent->city = $request->city;
            $agent->save();

            //get vendor by user_id 
            $vendor = Vendor::where('user_id',$data->id)->first();
            // update vendor_id on user table
            $user = User::find($vendor->user_id);;
            $user->vendor_id = $vendor->id;
            $user->update();

            return redirect()
                ->route('dashboard.agent')
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

       return view('admin.agent.form',compact('model','countries','setting'));
    }
    public function unactive(string $id)
    {

       $model = Vendor::find($id);
       $vendorid = User::where('vendor_id',$model->id)->first();

        $user = User::find($vendorid->id);
        $user->vendor_id = $model->id;
        $user->is_active = 0;
        $user->save();
        
        $model->is_active = 0;
        $model->save();

       return redirect()->back()->with('success', 'agent active successfully.');
    }
    public function active(string $id)
    {

       $model = Vendor::find($id);
       $vendorid = User::where('vendor_id',$model->id)->exists();

       if(!$vendorid){
            $user = new User();
            $user->first_name = $model->vendor_name;
            $user->email = $model->email;
            $user->password = Hash::make('password123');
            $user->vendor_id = $model->id;
            $user->mobile_phone = $model->phone;
            $user->is_active = 1;
            $user->role_id = 3;
            $user->save();

            
            $model->user_id = $user->id;
            $model->save();
       }else{
        
            $user = User::find($model->user_id);
            $user->vendor_id = $model->id;
            $user->is_active = 1;
            $user->save();

            $model->is_active = 1;
            $model->save();

       }

       return redirect()->back()->with('success', 'agent active successfully.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        
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
        $vendor->update();
        
        // update user
        $user = User::find($vendor->user_id);;
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->update();
        
        return redirect()
            ->route('dashboard.agent.edit', ['id' => $id])
            ->with('message', 'Data Agent berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = Vendor::findOrFail($id);
        $user = User::findOrFail($vendor->user_id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $vendor->delete();
        $user->delete();

        return redirect()
            ->route('dashboard.agent')
            ->with('message', 'Data Agent berhasil dihapus.');
    }
}
