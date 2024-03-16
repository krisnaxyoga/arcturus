<?php

namespace App\Http\Controllers\Admin\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\TransportDestination;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
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
        $data = TransportDestination::all();
        return view('admin.transport.destination',compact('data','setting'));
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

        $indonesiaprovinsi = getindonesiaprovinsi();
        $model = new TransportDestination;
        return view('admin.transport.formdestination',compact('model','setting','indonesiaprovinsi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required'
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data =  new TransportDestination();
            $data->destination = $request->destination;
            $data->state = $request->state;
            $data->save();

            return redirect()
            ->route('dashboard.transport.destination')
            ->with('message', 'Data saved!.');
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

        $indonesiaprovinsi = getindonesiaprovinsi();
        $model = TransportDestination::find($id);
        return view('admin.transport.formdestination',compact('model','setting','indonesiaprovinsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required'
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data = TransportDestination::find($id);
            $data->destination = $request->destination;
            $data->state = $request->state;
            $data->save();

            return redirect()
            ->route('dashboard.transport.destination')
            ->with('message', 'Data saved!.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = TransportDestination::find($id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $data->delete();

        return redirect()
            ->route('dashboard.transport.destination')
            ->with('message', 'Data destination deleted.');
    }
}
