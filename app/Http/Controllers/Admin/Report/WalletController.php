<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryWallet;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\PaymentGetwayTransaction;
use App\Mail\TopUpConfirmation;

use Illuminate\Support\Facades\Mail;

class WalletController extends Controller
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

        $data = PaymentGetwayTransaction::where('payment_method','BANK-TRANSFER-TOP-UP')->orderBy('created_at', 'desc')->get();

        $isee = PaymentGetwayTransaction::where('payment_method', 'BANK-TRANSFER-TOP-UP')->where('is_see',0)->get();
        foreach($isee as $show){
            $show->is_see = 1;
            $show->save();
        }

        return view('admin.wallet.index',compact('setting','data'));
    }

    public function confirmationtopup($id){
        $payment = PaymentGetwayTransaction::find($id);
        $payment->status = 200;
        $payment->save();

        $history = HistoryWallet::find($payment->trx_id);
        $history->status = 'success';
        $history->save();

        $user = User::find($history->user_id);
        $user->saldo = $history->total_saldo;
        $user->save();

        if (env('APP_ENV') == 'production') {
        Mail::to($history->users->email)->send(new TopUpConfirmation($history));
        }
        return redirect()->back()->with('message', 'Email send to agent');
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
