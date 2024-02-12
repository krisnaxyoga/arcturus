<?php

namespace App\Http\Controllers\Admin\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\ContractPrice;
use App\Models\Setting;
use App\Models\VendorAffiliate;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class HotelController extends Controller
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

        return view('admin.hotel.index',compact('data','setting'));
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

        return view('admin.hotel.form',compact('model', 'user', 'countries','setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        // dd($request->hasFile('image'));
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
               

            $data = new User();
            $data->first_name =  $request->firstname;
            $data->last_name = $request->lastname;
            $data->email = $request->email;
            $data->mobile_phone = $request->phone;
            $data->password = Hash::make('password123');
            $data->departement = '-';
            $data->position = 'master';
            $data->title = Str::random(8);
            $data->role_id = 2;
            $data->is_see = 0;
            $data->is_active = 0;
            $data->save();

            $member = new Vendor();
            $member->user_id = $data->id;
            $member->vendor_name = $request->vendor_name;
            $member->vendor_legal_name = $request->vendor_legal_name;
            $member->address_line1 = $request->address1;
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->type_vendor = 'hotel';
            $member->is_active = 0;
            $member->marketcountry = ["WORLDWIDE"];
            $member->system_markup = $request->markup;
            $member->hotel_star = $request->hotel_star;
            $member->recomend = $request->recomend; 
            
            if($request->affiliate){
                $member->affiliate = $request->affiliate;
                $Affiliate = Affiliate::where('code',$request->affiliate)->first();
                $Affiliate->hotelaffiliate = $Affiliate->hotelaffiliate + 1;
                $Affiliate->save();
            }
            $member->save();
            if($request->affiliate){
                $Affiliate = Affiliate::where('code',$request->affiliate)->first();
                $VendorAffiliate = new VendorAffiliate;
                $VendorAffiliate->vendor_id = $member->id;
                $VendorAffiliate->affiliate_id = $Affiliate->id;
                $VendorAffiliate->save();
            }

            


            $user = User::find($data->id);
            $user->vendor_id = $member->id;
            $user->save();


                // $data = new User();
                // $data->first_name = $request->firstname;
                // $data->last_name = $request->lastname;
                // $data->email = $request->email;
                // $data->password = Hash::make('password123');
                // $data->role_id = 2;
                // $data->save();
    
                // $vendor = new Vendor();
                // $vendor->user_id = $data->id;
                // $vendor->vendor_name = $request->vendor_name;
                // $vendor->vendor_legal_name = $request->vendor_legal_name;
                // $vendor->logo_img = '-';
                // $vendor->type_vendor = 'hotel';
                // $vendor->address_line1 = $request->address1;
                // $vendor->address_line2 = $request->address2;
                // $vendor->phone = $request->phone;
                // $vendor->email = $request->email;
                // $vendor->country = $request->country;
                // $vendor->state = $request->state;
                // $vendor->city = $request->city;
                // $vendor->system_markup = $request->markup;
                // $vendor->hotel_star = $request->hotel_star;
                // $vendor->recomend = $request->recomend; 
                // $vendor->affiliate = $request->affiliate;
                // if($request->affiliate){
                   
                //     $Affiliate = Affiliate::where('code',$request->affiliate)->first();
                //     $Affiliate->hotelaffiliate = $Affiliate->hotelaffiliate + 1;
                //     $Affiliate->save();
                // }
    
                // if($request->affiliate){
                //     $Affiliate = Affiliate::where('code',$request->affiliate)->first();
                //     $VendorAffiliate = new VendorAffiliate;
                //     $VendorAffiliate->vendor_id = $vendor->id;
                //     $VendorAffiliate->affiliate_id = $Affiliate->id;
                //     $VendorAffiliate->save();
                // }
                // $vendor->save();


    
                // //get vendor by user_id 
                // $vendor = Vendor::where('user_id',$data->id)->first();
                // // update vendor_id on user table
                // $user = User::find($vendor->user_id);;
                // $user->vendor_id = $vendor->id;
                // $user->update();

                return redirect()
                ->route('dashboard.hotel')
                ->with('message', 'Data Saved!.');
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
 
        return view('admin.hotel.form',compact('model','countries','setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $country = get_country_lists();
        //dd($request);
        $validator =  Validator::make($request->all(), [
            'vendor_name' => 'nullable',
            'email' => 'nullable'
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
        $vendor->system_markup = $request->markup;
        $vendor->hotel_star = $request->hotel_star;
        $vendor->recomend = $request->recomend;
       
        if($request->affiliate == null){
            
            $Affiliate1 = Affiliate::where('code',$vendor->affiliate)->first();
            if($Affiliate1){
                $Affiliate1->hotelaffiliate = $Affiliate1->hotelaffiliate - 1;
                $Affiliate1->save();
                
                $VendorAffiliate = VendorAffiliate::where('vendor_id',$vendor->id)->where('affiliate_id', $Affiliate1->id)->first();
                if($VendorAffiliate){
                    $VendorAffiliate->delete(); 
                }
               
            }
            $vendor->affiliate = $request->affiliate;
        }else{
            if($request->affiliate && $vendor->affiliate != $request->affiliate){

                $Affiliate = Affiliate::where('code',$request->affiliate)->first();
                if($Affiliate){
                    $Affiliate1 = Affiliate::where('code',$vendor->affiliate)->first();
                    if($Affiliate1){
                        $Affiliate1->hotelaffiliate = $Affiliate1->hotelaffiliate - 1;
                        $Affiliate1->save();
                        
                        $VendorAffiliate = VendorAffiliate::where('vendor_id',$vendor->id)->where('affiliate_id', $Affiliate1->id)->first();
                        if($VendorAffiliate){
                            $VendorAffiliate->delete(); 
                        }
                       
                    }
                    $Affiliate->hotelaffiliate = $Affiliate->hotelaffiliate + 1;
                    $Affiliate->save();
    
                    $VendorAffiliate1 = new VendorAffiliate;
                    $VendorAffiliate1->vendor_id = $vendor->id;
                    $VendorAffiliate1->affiliate_id = $Affiliate->id;
                    $VendorAffiliate1->save();
    
                    $vendor->affiliate = $request->affiliate;
                }else{
                    return redirect()
                    ->route('dashboard.hotel.edit', ['id' => $id])
                    ->with('message', 'Affilate not found!.');
                }
                
            }
    
            if($request->affiliate && $vendor->affiliate == $request->affiliate){
                $Affiliate = Affiliate::where('code',$request->affiliate)->first();
                $VendorAffiliate = VendorAffiliate::where('vendor_id',$vendor->id)->where('affiliate_id', $Affiliate->id)->first();
                if($VendorAffiliate){
    
                    $VendorAffiliate->delete();
    
                    $datAffiliate = new VendorAffiliate;
                    $datAffiliate->vendor_id = $vendor->id;
                    $datAffiliate->affiliate_id = $Affiliate->id;
                    $datAffiliate->save();
    
                }else{
                    $VendorAffiliate = new VendorAffiliate;
                    $VendorAffiliate->vendor_id = $vendor->id;
                    $VendorAffiliate->affiliate_id = $Affiliate->id;
                    $VendorAffiliate->save();
                }
               
               
            }
        }
        $vendor->save();

       // Mengecek apakah ada ContractPrice yang sesuai dengan user_id vendor
        $contractPrices = ContractPrice::where('user_id', $vendor->user_id)->get();

        foreach ($contractPrices as $contractPrice) {
            $contractPrice->recomend = $request->recomend; // Menggunakan nilai recomend yang sama dengan vendor
            $contractPrice->update();
        }

        
        // update user
        $user = User::find($vendor->user_id);;
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->update();
        
        return redirect()
            ->route('dashboard.hotel.edit', ['id' => $id])
            ->with('message', 'Data Hotel updated!.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hotel = Vendor::findOrFail($id);
        $user = User::findOrFail($hotel->user_id);

        // Lakukan tindakan lain sebelum penghapusan jika diperlukan
        $hotel->delete();
        $user->delete();

        return redirect()
            ->route('dashboard.hotel')
            ->with('message', 'Data deleted.');
    }

    public function loginhotel($id){
        if (Auth::check() && Auth::user()->role_id == 1) {
            // Logout admin
            Auth::logout();

            // Lakukan otentikasi sebagai akun hotel
            Auth::loginUsingId($id);

            // Redirect ke halaman hotel
            return redirect('/vendordashboard');
        }
    }
}
