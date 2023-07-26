<?php

namespace App\Http\Controllers\Agent\MyProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $country = get_country_lists();
        $iduser = auth()->user()->id;
        
        $data = User::where('id',$iduser)->with('vendors')->first();
        //dd($data);
        $contacts = User::where('vendor_id',$data->vendors->id)->get();

        return inertia('Agent/MyProfile/Index',[
            'data'=>$data,
            'contacts'=>$contacts,
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
                $logo->move(public_path('agent/logo'), $filename);
    
                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
                $logo = "/agent/logo/".$filename;
            }else{
                $logo = $vendor[0]->logo_img;
            }
            

            // $data = User::find($id);
            // $data->first_name = $request->firstname;
            // $data->last_name = $request->lastname;
            // $data->email = $request->email;
            // $data->profile_image = $logo;
            // $data->save();

            $member = Vendor::find($vendor[0]->id);
            $member->city = $request->city;
            $member->state = $request->state;
            $member->country = $request->country;
            $member->area = $request->area;
            $member->location = $request->location;
            $member->logo_img = $logo;
            $member->vendor_name = $request->busisnessname;
            $member->vendor_legal_name = $request->legalname;
            $member->address_line1 = $request->address;
            $member->address_line2 = $request->address2;
            $member->phone = $request->phone;
            $member->email = $request->email;
            $member->map_latitude = $request->latitude;
            $member->map_longitude	= $request->longitude;
            
            $member->save();
            

            // dd($member->id);
            return redirect()
                ->route('agent.myprofile')
                ->with('success', 'data updated success');;
        }
    }


    
    /**
     * Show the form for creating a new resource.
     */
    public function contactcreate()
    {
        $iduser = auth()->user()->id;
        $data = Vendor::where('user_id',$iduser)->with('users')->first();
        
        return inertia('Agent/MyProfile/Contact/Create',[
            'data' => $data
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function contactstore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'email' => 'email|required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $id = auth()->user()->id;
                $data =  new User();
                $data->vendor_id = $request->vendorid;
                $data->departement = $request->departement;
                $data->position = $request->position;
                $data->first_name = $request->firstname;
                $data->last_name = $request->lastname;
                $data->mobile_phone = $request->phone;
                $data->email = $request->email;
                $data->role_id = 3; // agent
                $data->password = Hash::make('password123');
                $data->save();
            
            return redirect()
            ->route('agent.myprofile')
            ->with('success', 'Data contact saved!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function contactedit(string $id)
    {
        $iduser = auth()->user()->id;
        $data = User::where('id',$iduser)->with('vendors')->first();
        
        $contact = User::find($id);
        return inertia('Agent/MyProfile/Contact/Edit',[
            'data' => $data,
            'contact' =>$contact
        ]);
    }

    public function passwordchange()
    {
        $iduser = auth()->user()->id;
        $data = User::where('id',$iduser)->with('vendors')->first();

        return inertia('Agent/MyProfile/Contact/PasswordChange',[
            'data' => $data
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
    /**
     * Update the specified resource in storage.
     */
    public function contactupdate(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'email' => 'email|required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $data = User::find($id);
                $data->departement = $request->departement;
                $data->position = $request->position;
                $data->first_name = $request->firstname;
                $data->last_name = $request->lastname;
                $data->mobile_phone = $request->phone;
                $data->email = $request->email;
                $data->update();
            
            return redirect()
            ->route('agent.myprofile')
            ->with('success', 'Data contact updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function contactdestroy(string $id)
    {
        $data = User::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data deleted!');
    }
}
