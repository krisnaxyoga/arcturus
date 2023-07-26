<?php

namespace App\Http\Controllers\Vendor\Payout;

use App\Models\User;

use App\Models\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->first();
        return inertia('Vendor/Payout/Index',[
            'data'=>$vendor
        ]);
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(),[
            'email' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

            $data = Vendor::where('user_id',$id)->first();
            $data->bank_name = $request->bank;
            $data->bank_account = $request->bankaccount;
            $data->swif_code = $request->swifcode;
            $data->save();
            
            return redirect()
                ->route('vendors.payouts.index')
                ->with('success', 'data updated success');;
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
      //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
