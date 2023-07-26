<?php

namespace App\Http\Controllers\Vendor\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class MyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $country = get_country_lists();
        $iduser = auth()->user()->id;
        $data = Vendor::with('users')->where('user_id',$iduser)->get();
        // dd($data, $iduser);
        return inertia('Vendor/MyProfile/Index',[
            'data'=>$data,
            'country'=>$country
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
            // $member->email = $request->email;
            $member->save();
            

            // dd($member->id);
            return redirect()
                ->route('vendor.myprofile')
                ->with('success', 'data updated success');;
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
