<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttributeRoom;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AttributeController extends Controller
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


        $data = AttributeRoom::where('user_id',1)->get();
        return view('admin.attribute.index',compact('data','setting'));
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

        $model = new AttributeRoom;

        return view('admin.attribute.form',compact('model','setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $id = auth()->user()->id;
                $data =  new AttributeRoom();
                $data->user_id = $id;
                $data->name = $request->name;
                $data->description = $request->description;
                $data->save();
    
                return redirect()
                ->route('dashboard.attribute')
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

        $model = AttributeRoom::find($id);
 
        return view('admin.attribute.form',compact('model','setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator =  Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        //update vendor
        $data = AttributeRoom::find($id);
        $data->name = $request->name;
        $data->description = $request->description;
        $data->update();
        
        return redirect()
            ->route('dashboard.attribute.edit', ['id' => $id])
            ->with('message', 'Data Attribute berhasil diupdate.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = AttributeRoom::find($id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $data->delete();

        return redirect()
            ->route('dashboard.attribute')
            ->with('message', 'Data Attribute berhasil dihapus.');
    }
}
