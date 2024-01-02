<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Affiliate;
use App\Models\Vendor;
use App\Models\Setting;
use App\Models\VendorAffiliate;

class AffiliatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        if($be_code == $fo_code){

            session(['auth_code' => $user->auth_code]);
            session(['id_affiliate' => $id]);
            session(['name_affiliate' => $user->name]);

            $hotel = Vendor::where('affiliate',$user->code)->get();

            $vendoraffiliate = VendorAffiliate::orderBy('created_at')->get();
            return view('affiliate.home',compact('user','hotel','vendoraffiliate'));

        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function profile($code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        if($be_code == $fo_code){
            $hotel = Vendor::where('affiliate',$user->code)->get();
            $settings = Setting::first();
            $vendoraffiliate = VendorAffiliate::orderBy('created_at')->get();
            return view('affiliate.profile',compact('user','hotel','vendoraffiliate','settings','be_code'));

        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function link($code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        if($be_code == $fo_code){
            $hotel = Vendor::where('affiliate',$user->code)->get();

            $vendoraffiliate = VendorAffiliate::orderBy('created_at')->get();
            return view('affiliate.link',compact('user','hotel','vendoraffiliate'));

        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }

    }
    /**
     * Display the specified resource.
     */
    public function hotel($code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        if($be_code == $fo_code){
            $hotel = Vendor::where('affiliate',$user->code)->get();

            $vendoraffiliate = VendorAffiliate::orderBy('created_at')->get();
            return view('affiliate.hotel',compact('user','hotel','vendoraffiliate'));

        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function logout($code,$id)
    {
        $user = Affiliate::where('id',$id)->first();
        $be_code = Crypt::decrypt($user->auth_code);
        $fo_code = Crypt::decrypt($code);

        if($be_code == $fo_code){
            session()->forget('auth_code');
            session()->forget('id_affiliate');
            session()->forget('name_affiliate');

           return redirect()
           ->route('auth.affiliator.login')
           ->with('message', 'Logout!.'); 
        }else{
            return response()->json(['message' => 'sorry Unauthorized']);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function dologin(Request $request)
    {
        $user = Affiliate::where('email',$request->email)->first();

        $password = Crypt::decrypt($user->auth_code);
        
        if($request->password == $password){
            $id = $user->id;
            $code = Crypt::encrypt($request->password);

            // $this->index($code,$id);
            return redirect()
            ->route('auth.affiliator',['code'=>$code,'id'=>$id])
            ->with('message', 'Data Saved!.');
        }else{
            return back()->with('error', 'email or password wrong');
        }
        
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function login()
    {
        return view('affiliate.login');
    }
}
