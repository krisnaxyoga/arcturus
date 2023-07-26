<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

        $data = User::all();
        return view('admin.user.index',compact('data','setting'));
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

        $model = new User;
        $vendors = Vendor::pluck('vendor_name', 'id');
        $roles = Role::pluck('role_name', 'id');

        return view('admin.user.form',compact('model','vendors', 'roles', 'setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'role_id' => 'required',
            'firstname' => 'required',
            'email' => 'required',
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $id = auth()->user()->id;
                $data =  new User();
                $data->vendor_id = $request->vendor_id;
                $data->departement = $request->departement;
                $data->position = $request->position;
                $data->role_id = $request->role_id;

                $data->first_name = $request->firstname;
                $data->last_name = $request->lastname;
                $data->mobile_phone = $request->phone;
                $data->email = $request->email;
                $data->password = Hash::make('password123');
                $data->save();
    
                return redirect()
                ->route('dashboard.user')
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

        $model = User::find($id);
        $vendors = Vendor::pluck('vendor_name', 'id');
        $roles = Role::pluck('role_name', 'id');

       return view('admin.user.form',compact('model','vendors', 'roles', 'setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);

        $validator =  Validator::make($request->all(), [
            'vendor_id' => 'required',
            'role_id' => 'required',
            'firstname' => 'required',
            'email' => 'required',
        ]);

        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        //update vendor
        $data = User::find($id);
        $data->vendor_id = $request->vendor_id;
        $data->departement = $request->departement;
        $data->position = $request->position;
        $data->role_id = $request->role_id;

        $data->first_name = $request->firstname;
        $data->last_name = $request->lastname;
        $data->mobile_phone = $request->phone;
        $data->email = $request->email;
        $data->update();
        
        return redirect()
            ->route('dashboard.user.edit', ['id' => $id])
            ->with('message', 'Data User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::find($id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $data->delete();

        return redirect()
            ->route('dashboard.user')
            ->with('message', 'Data User berhasil dihapus.');
    }
}
