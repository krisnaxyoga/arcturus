<?php

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Setting;
use App\Mail\InviteAffiliate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class AffiliateController extends Controller
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


        $data = Affiliate::orderBy('created_at','desc')->get();
        return view("admin.affiliate.index",compact('data','setting'));
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

        $model = new Affiliate;

        return view("admin.affiliate.form",compact('model','setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:affiliates,email',
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

                $authcode = Str::random(8);
                $affliatecode = Str::random(4);

                $data =  new Affiliate();
                $data->name = $request->name;
                $data->email = $request->email;
                $data->auth_code = Crypt::encrypt($authcode);
                $data->code = $affliatecode;
                $data->saldo = 0;
                $data->save();

                return redirect()
                ->route('admin.afiliate')
                ->with('message', 'Data Saved!.');
            }
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

        $model = Affiliate::find($id);

        return view("admin.affiliate.form",compact('model','setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

                $data =  Affiliate::find($id);
                $data->name = $request->name;
                $data->email = $request->email;
                $data->save();

                return redirect()
                ->route('admin.afiliate')
                ->with('message', 'Data Saved!.');
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Affiliate::find($id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $data->delete();

        return redirect()
            ->route('admin.afiliate')
            ->with('message', 'Data Deleted.');
    }

    public function invite($id)
    {

        $model = Affiliate::find($id);
        $data = $model;

        if (env('APP_ENV') == 'production') {
        Mail::to($model->email)->send(new InviteAffiliate($data));
        }
        return redirect()->back()->with('message', 'Agent Transport invite!');
    }
}
