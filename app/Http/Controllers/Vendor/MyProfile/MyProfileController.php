<?php

namespace App\Http\Controllers\Vendor\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\AgentMarkupSetup;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use App\Models\Slider;
use App\Models\RoomHotel;
use App\Models\WidrawVendor;
use Carbon\Carbon;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Str;

class MyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $country = get_country_lists();
        $property = type_property();
        $iduser = auth()->user()->id;
        $indonesiaprovinsi = getindonesiaprovinsi();
        $data = Vendor::with('users')->where('user_id',$iduser)->get();
        // dd($data, $iduser);
        $markup = AgentMarkupSetup::where('user_id',$iduser)->first();
        $slider = Slider::where('user_id',$iduser)->get();
        $vendor= Vendor::with('users')->where('user_id',$iduser)->first();
        // Check if "WORLDWIDE" is already present
        if (!in_array("WORLDWIDE", $vendor->marketcountry)) {
            // Add "WORLDWIDE" only if it's not present
            $vendor->marketcountry = ["WORLDWIDE"];
            $vendor->save();
        }


        return inertia('Vendor/MyProfile/Index',[
            'data' => $data,
            'country'=> $country,
            'markup' => $markup,
            'banner' => $slider,
            'vendor' => $vendor,
            'property' => $property,
            'indonesiaprovinsi' => $indonesiaprovinsi,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $id = auth()->user()->id;
        $vendor = Vendor::where('user_id',$id)->get();
        $validator = Validator::make($request->all(),[
            'email' => 'nullable',
            'logo' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $filename = time() . '.' . $logo->getClientOriginalExtension();
                // $logo->move(public_path('hotel/logo'), $filename);
                $compressedImage = Image::make($logo->getRealPath());
                $compressedImage->resize(300, 200)->save(public_path('hotel/logo/' . $filename), 90); // 90 adalah kualitas kompresi yang lebih baik

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
                $logo = "/hotel/logo/".$filename;
            }else{
                $logo = $vendor[0]->logo_img;
            }


            $data = User::find($id);

            if($data->position == 'master' && $data->title != $request->code){
                $user = User::where('title',$data->title)->where('position','sub-master')->get();
                foreach($user as $key=>$itemuser){
                    $itemuser->title = Str::random(6).'key'.$key;
                    $itemuser->position = 'master';
                    $itemuser->save();
                }
            }

            

            $data->first_name = $request->firstname;
            $data->last_name = $request->lastname;
            $data->email = $request->email;
            $data->profile_image = $logo;
            if ($data->title != $request->code) {
                $user1 = User::where('title', $request->code)->first();
            
                if (!$user1) {
                    $data->position = 'master';
                } else {
                    if ($data->position != 'master') {
                        $data->position = 'sub-master';
                    }
                }
            }
            $data->title = $request->code;
            $data->save();

            $member = Vendor::find($vendor[0]->id);
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->logo_img = $logo;
            $member->vendor_name = $request->busisnessname;
            $member->address_line1 = $request->address;
            $member->address_line2 = $request->address2;
            $member->phone = $request->phone;
            $member->bank_name = $request->bank;
            $member->bank_account = $request->bankaccount;
            $member->swif_code = $request->swifcode;
            $member->bank_address = $request->bankaddress;
            $member->account_number = $request->accountnumber;
            $member->email_reservation = $request->email_reservation;
            $member->highlight = $request->highlight;
            $member->description = $request->description;
            $member->type_property = $request->type_property;
            // $member->email = $request->email;
            
            $member->marketcountry = explode(",",$request->distribute);
            $member->save();

            $markup = AgentMarkupSetup::where('user_id',$id)->exists();

            if(!$markup){
                $mark = new AgentMarkupSetup;
                $mark->user_id = $id;
                $mark->vendor_id = $vendor[0]->id;
                $mark->service = $request->service;
                $mark->tax = 0;
                $mark->markup_price = 0;
                $mark->save();
            }else{
                $mark2 = AgentMarkupSetup::where('user_id',$id)->first();
                $mark2->user_id = $id;
                $mark2->vendor_id = $vendor[0]->id;
                $mark2->service = $request->service;
                $mark2->tax = 0;
                $mark2->save();
            }

            // dd($member->id);
            return redirect()
                ->route('vendor.myprofile')
                ->with('success', 'data updated success');
        }
    }

    public function addbanner(Request $request){
        $validator = Validator::make($request->all(), [
            'banner' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

            if ($request->hasFile('banner')) {
                $banner = $request->file('banner');
                $filename = time() . '.' . $banner->getClientOriginalExtension();
                // $banner->move(public_path('slider'), $filename);
                $compressedImage = Image::make($banner->getRealPath());
                $compressedImage->resize(1500, 1000)->save(public_path('slider/' . $filename), 90); // 90 adalah kualitas kompresi yang lebih baik

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename= "";
            }

            $feature = "/slider/".$filename;

            $iduser = auth()->user()->id;

            $slider = Slider::where('user_id',$iduser)->count();
            if($slider >= 3){
                return redirect()->back()->with('success', 'sorry your picture exceeds 3 pictures!, please delete one of them to upload again');
            }else{
                $data =  new Slider();
                $data->user_id = $iduser;
                $data->title = $request->title;
                $data->description = $request->description;
                $data->image = $feature;
                $data->save();
            }

            return redirect()->back()->with('success', 'data saved!');

        }
    }
    /**
     * Remove the specified resource from storage.
     */

     public function destroybanner(string $id)
     {
        $data =  Slider::find($id);
        if (File::exists(public_path($data->image))) 
        {
            File::delete(public_path($data->image));
        }
        $data->delete();

        return redirect()->back()->with('success', 'data deleted!');

     }
    public function destroy(string $id)
    {
        //
    }

    public function passwordchange()
    {
        $iduser = auth()->user()->id;
        $data = Vendor::with('users')->where('user_id',$iduser)->get();
        $vendor = Vendor::with('users')->where('user_id',$iduser)->first();
        return inertia('Vendor/MyProfile/ChangePassword',[
            'data' => $data,
            'vendor' => $vendor
        ]);
    }

    public function updatepassword(Request $request){
        $iduser = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $data = User::find($iduser);
                $data->password = Hash::make($request->password);
                $data->update();

        return redirect()->back()->with('success', 'Password Change');
        }

    }

    public function property(){
        $iduser = auth()->user()->id;
        $title = auth()->user()->title;
        $data = User::with('vendors')->where('title',$title)->get();
        $vendor = Vendor::with('users')->where('user_id',$iduser)->first();
        return inertia('Vendor/Property/Index',[
            'data' => $data,
            'vendor' => $vendor
        ]);
    }
    public function propertycreate(Request $request){
        $country = get_country_lists();
        $iduser = auth()->user()->id;
        $vendor = Vendor::with('users')->where('user_id',$iduser)->first();
        return inertia('Vendor/Property/Create',[
            'vendor' => $vendor,
            'country'=>$country
        ]);
    }

    public function propertystore(Request $request){
        $iduser = auth()->user()->id;
        $title = auth()->user()->title;
        $data = User::with('vendors')->where('title',$title)->where('role_id',2)->where('position','sub-master')->get();
        $vendor = Vendor::with('users')->where('user_id',$iduser)->first();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('success', 'Sorry!, the email you entered already exists');
        } else {
            $data = new User();
            $data->first_name = $request->firstname;
            $data->last_name = '-';
            $data->email = $request->email;
            $data->mobile_phone = $request->phone;
            $data->password = Hash::make('password123');
            $data->departement = '-';
            $data->position = 'sub-master';
            $data->title = $title;
            $data->role_id = 2;
            $data->is_active = 1;
            $data->save();

            $member = new Vendor();
            $member->user_id = $data->id;
            $member->vendor_name = $request->firstname;
            $member->vendor_legal_name = '-';
            $member->address_line1 = '-';
            $member->city = '-';
            $member->state = '-';
            $member->country = $request->country;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->type_vendor = 'hotel';
            $member->is_active = 1;
            $member->save();

            $user = User::find($data->id);
            $user->vendor_id = $member->id;
            $user->save();

            return redirect()->back()->with('success', 'Property Add success');
        }
    }

    public function loginproperty($id){
        if (Auth::check() && Auth::user()->role_id == 2) {
            // Logout admin
            Auth::logout();

            // Lakukan otentikasi sebagai akun hotel
            Auth::loginUsingId($id);
            $iduser = $id;
            $vendor = Vendor::where('user_id',$iduser)->with('users')->first();
            $totalincome = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->sum('pricenomarkup');
            $totalbooking = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->count();
            $bookingsuccess = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->count();
            $pendingpayment = Booking::where('vendor_id',$vendor->id)->where('booking_status','unpaid')->count();
            $booking = Booking::where('vendor_id',$vendor->id)->whereNotIn('booking_status', ['-', ''])->with('vendor','users')->orderBy('created_at', 'desc')->get();
            $acyive = auth()->user()->is_active;
            $roomhotel1 = Booking::where('vendor_id',$vendor->id)->where('booking_status','paid')->get();
            $roomhotel = 0;
            foreach($roomhotel1 as $item){
                $roomhotel += $item->night * $item->total_room;
            }

            $widraw = WidrawVendor::where('vendor_id', $vendor->id)
            ->whereDate('created_at', '=', Carbon::today())
            ->get();
            // Redirect ke halaman hotel
            // return redirect('/vendordashboard');
            // Menyertakan variabel position
            Inertia::share('position', 'master');

            // Redirect ke halaman hotel
            return Inertia::render('Vendor/Index',[
                'income'=>$totalincome,
                'booking'=>$totalbooking,
                'success'=>$bookingsuccess,
                'pending'=>$pendingpayment,
                'data'=>$booking,
                'widraw'=>$widraw,
                'totalroom' => $roomhotel,
                'vendor' => $vendor,
            ]);
        }
    }
}
