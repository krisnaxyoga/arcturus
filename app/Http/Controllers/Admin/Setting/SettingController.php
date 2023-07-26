<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = get_country_lists();
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        return view('admin.setting.form',compact('setting', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'photo' => 'required|mimes:png,jpg,jpeg|max:2048',
            'email' => 'required|email',
            'name' => 'required'
        ]);


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $photo = $request->file('photo');
            $userid = auth()->user()->id;
            $data = new Setting();
            $data->user_id =$userid;
            $data->company_name = $request->name;
            $data->business_permit_number = $request->permit;
            $data->description = $request->description;

            $data->address_line1 = $request->address1;
            $data->address_line2 = $request->address2;
            $data->country = $request->country;
            $data->state = $request->state;
            $data->city = $request->city;
            $data->telephone = $request->telephone;
            $data->fax = $request->fax;
            $data->email = $request->email;
            $data->zipcode = $request->zipcode;

            if($photo) {
                $filename = date('Y-m-d').$photo->getClientOriginalName();
                $path = 'logo/'.$filename;
        
                Storage::disk('public')->put($path,file_get_contents($photo));

                $data->logo_image = $filename;
                $data->url_logo_image = $path;
                $data->url_website = $request->url_website;
            }
            $data->save();

            return redirect()
                ->route('dashboard.setting')
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator =  Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data = Setting::find($id);
            $userid = auth()->user()->id;
            $data->user_id = $userid;
            $data->company_name = $request->name;
            $data->business_permit_number = $request->permit;
            $data->description = $request->description; 

            $data->address_line1 = $request->address1;
            $data->address_line2 = $request->address2;
            $data->country = $request->country;
            $data->state = $request->state;
            $data->city = $request->city;
            $data->telephone = $request->telephone;
            $data->fax = $request->fax;
            $data->email = $request->email;
            $data->zipcode = $request->zipcode;

            $photo = $request->file('photo');
            if($photo) {
                $filename = date('Y-m-d').$photo->getClientOriginalName();
                $path = 'logo/'.$filename;
        
                Storage::disk('public')->put($path,file_get_contents($photo));

                $data->logo_image = $filename;
                $data->url_logo_image = $path;
                $data->url_website = $request->url_website;
            }
            
            $data->update();

            return redirect()
                ->route('dashboard.setting')
                ->with('message', 'Data berhasil diupdate.');
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
