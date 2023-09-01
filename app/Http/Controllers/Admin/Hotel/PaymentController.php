<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Vendor;
use App\Models\WidrawVendor;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
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

        return view('admin.paymenthotel.index',compact('data','setting'));
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
    public function edit(string $id)
    {
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $model = Vendor::find($id);
        $widraw = WidrawVendor::where('vendor_id',$id)->first();

        return view('admin.paymenthotel.form',compact('model','setting','widraw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable'
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('widraw'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename= "";
            }

            $feature = "/widraw/".$filename;

                $userid = auth()->user()->id;
                $data =  new WidrawVendor();
                $data->user_id = $userid;
                $data->vendor_id = $id;
                $data->image = $feature;
                $data->save();

                return redirect()
                ->route('dashboard.paymenttohotel.index')
                ->with('message', 'Data berhasil disimpan.');
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
