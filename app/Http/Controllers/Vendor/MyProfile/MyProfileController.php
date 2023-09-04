<?php

namespace App\Http\Controllers\Vendor\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\AgentMarkupSetup;
use Illuminate\Support\Facades\Validator;
use App\Models\Slider;

use Illuminate\Support\Facades\Hash;

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
        $data = Vendor::with('users')->where('user_id',$iduser)->get();
        // dd($data, $iduser);
        $markup = AgentMarkupSetup::where('user_id',$iduser)->first();
        $slider = Slider::where('user_id',$iduser)->get();
        $vendor= Vendor::with('users')->where('user_id',$iduser)->first();

        return inertia('Vendor/MyProfile/Index',[
            'data' => $data,
            'country'=> $country,
            'markup' => $markup,
            'banner' => $slider,
            'vendor' => $vendor,
            'property' => $property,
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
                $logo->move(public_path('hotel/logo'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
                $logo = "/hotel/logo/".$filename;
            }else{
                $logo = $vendor[0]->logo_img;
            }


            $data = User::find($id);
            $data->first_name = $request->firstname;
            $data->last_name = $request->lastname;
            $data->email = $request->email;
            $data->profile_image = $logo;
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
            $member->save();

            $markup = AgentMarkupSetup::where('user_id',$id)->exists();

            if(!$markup){
                $mark = new AgentMarkupSetup;
                $mark->user_id = $id;
                $mark->vendor_id = $vendor[0]->id;
                $mark->service = $request->service;
                $mark->tax = $request->tax;
                $mark->markup_price = 0;
                $mark->save();
            }else{
                $mark2 = AgentMarkupSetup::where('user_id',$id)->first();
                $mark2->user_id = $id;
                $mark2->vendor_id = $vendor[0]->id;
                $mark2->service = $request->service;
                $mark2->tax = $request->tax;
                $mark2->save();
            }

            // dd($member->id);
            return redirect()
                ->route('vendor.myprofile')
                ->with('success', 'data updated success');;
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
                $banner->move(public_path('slider'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename= "";
            }

            $feature = "/slider/".$filename;

            $iduser = auth()->user()->id;

            $data =  new Slider();
            $data->user_id = $iduser;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->image = $feature;
            $data->save();

            return redirect()->back()->with('success', 'data saved!');

        }
    }
    /**
     * Remove the specified resource from storage.
     */

     public function destroybanner(string $id)
     {
        $data =  Slider::find($id);
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
}
