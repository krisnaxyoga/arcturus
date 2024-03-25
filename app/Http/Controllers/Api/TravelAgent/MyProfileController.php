<?php

namespace App\Http\Controllers\Api\TravelAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller
{
    public function index()
    {
        try {
            $country = get_country_lists();
            $iduser = auth()->user()->id;

            $data = User::where('id',$iduser)->with('vendors')->first();
            //dd($data);
            $contacts = User::where('vendor_id',$data->vendors->id)->get();

            return response()->json([
                'data'=>$data,
                'contacts'=>$contacts,
                'country'=>$country
            ],200);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
        
    }

    public function update(Request $request)
    {
        try {
             // dd($request->all());
            $id = auth()->user()->id;
            $vendor = Vendor::where('user_id',$id)->first();
            $validator = Validator::make($request->all(),[
                'email' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                if ($request->hasFile('logo')) {
                    $logo = $request->file('logo');
                    $filename = time() . '.' . $logo->getClientOriginalExtension();
                    $logo->move(public_path('agent/logo'), $filename);

                    // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
                    $logo = "/agent/logo/".$filename;
                }else{
                    $logo = $vendor->logo_img;
                }


                // $data = User::find($id);
                // $data->first_name = $request->firstname;
                // $data->last_name = $request->lastname;
                // $data->email = $request->email;
                // $data->profile_image = $logo;
                // $data->save();

                $member = Vendor::find($vendor->id);
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
                $member->bank_name = $request->bank;
                $member->bank_account = $request->bankaccount;
                $member->swif_code = $request->swifcode;
                $member->bank_address = $request->bankaddress;
                $member->account_number = $request->accountnumber;
                
                $member->marketcountry = explode(",",$request->distribute);
                // $member->credit_limit = $request->limit;
                // $member->credit_used = $request->used;
                // $member->credit_saldo = $request->saldo;
                $member->save();

                return response()->json([
                    'message'=>'data updated success'
                ],200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }

     /**
     * Show the form for creating a new resource.
     */
    public function contactcreate()
    {
        try {
            $iduser = auth()->user()->id;
            $data = User::where('id',$iduser)->with('vendors')->first();

            return response()->json([
                'data' => $data
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
        
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function contactstore(Request $request)
    {
        try {
             // dd($request->all());
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
                'email' => 'email|required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
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

                return response()->json([
                    'message'=> 'Data contact saved!'
                ],200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function contactedit(string $id)
    {
        try {
            $iduser = auth()->user()->id;
            $data = User::where('id',$iduser)->with('vendors')->first();
    
            $contact = User::find($id);
    
            return response()->json([
                'data' => $data,
                'contact' =>$contact
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }

    public function passwordchange()
    {
        try {
            $iduser = auth()->user()->id;
            $data = User::where('id',$iduser)->with('vendors')->first();
    
            return response()->json([
                'data' => $data
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }

    public function updatepassword(Request $request){
        try {
            $iduser = auth()->user()->id;
            $validator = Validator::make($request->all(), [
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                    $data = User::find($iduser);
                    $data->password = Hash::make($request->password);
                    $data->update();

            return response()->json([
                'message' =>  'Password Change'
            ],200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
        

    }
    /**
     * Update the specified resource in storage.
     */
    public function contactupdate(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
                'email' => 'email|required'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                    $data = User::find($id);
                    $data->departement = $request->departement;
                    $data->position = $request->position;
                    $data->first_name = $request->firstname;
                    $data->last_name = $request->lastname;
                    $data->mobile_phone = $request->phone;
                    $data->email = $request->email;
                    $data->update();
    
                return response()->json([
                    'message' => 'Data contact updated!'
                ],200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function contactdestroy(string $id)
    {
        try {
            $data = User::find($id);
            $data->delete();
    
            return response()->json([
                'message' => 'Data deleted!'
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }
}
