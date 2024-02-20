<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Support\Facades\File;
use App\Models\Popup;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datapop = new Popup;
        $countries = get_country_lists();
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $slide = Slider::all();
        $popup = Popup::all();
        return view('admin.setting.form',compact('setting', 'countries','slide','popup','datapop'));
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

        $validator =  Validator::make($request->all(), [
            'photo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'email' => 'nullable|email',
            'name' => 'required'
        ]);


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('logo/system'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename= "";
            }

            $feature = "/logo/system/".$filename;

            $userid = auth()->user()->id;
            $data = new Setting();
            $data->user_id =$userid;
            $data->company_name = $request->name;
            $data->business_permit_number = $request->permit;
            $data->description = $request->description;

            $data->address_line1 = $request->address1;
            $data->address_line2 = $request->address2;
            $data->country = $request->country;
            $data->state = $request->state;
            $data->city = $request->city;
            $data->telephone = $request->telephone;
            $data->fax = $request->fax;
            $data->email = $request->email;
            $data->zipcode = $request->zipcode;
            $data->logo_image = $feature;
            $data->save();

            return redirect()
                ->route('dashboard.setting')
                ->with('message', 'Data berhasil disimpan.');
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
        $validator =  Validator::make($request->all(), [
            'email' => 'nullable|email',
            'name' => 'required'
        ]);
// dd($request->all());
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $data = Setting::find($id);

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                if (File::exists(public_path($data->logo_image))) 
                    {
                        File::delete(public_path($data->logo_image));
                    }

                $filename = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('logo/system'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename = $data->logo_image;
            }
            
            $feature = "/logo/system/".$filename;
            // dd($feature);
            $userid = auth()->user()->id;
            $data->user_id = $userid;
            $data->company_name = $request->name;
            $data->business_permit_number = $request->permit;
            $data->description = $request->description; 

            $data->address_line1 = $request->address1;
            $data->address_line2 = $request->address2;
            $data->country = $request->country;
            $data->state = $request->state;
            $data->city = $request->city;
            $data->telephone = $request->telephone;
            $data->fax = $request->fax;
            $data->email = $request->email;
            $data->zipcode = $request->zipcode;

            $data->logo_image = $feature;
            $data->url_logo_image = $feature;

            $data->save();

            return redirect()
                ->route('dashboard.setting')
                ->with('message', 'Data berhasil diupdate.');
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroyslider(string $id)
    {
        $data = Slider::find($id);
        $data->delete();
        return redirect()
        ->route('dashboard.setting')
        ->with('message', 'Data deleted.');
    }

    public function storeslider(Request $request){
        $validator =  Validator::make($request->all(), [
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'description' => 'nullable',
            'title' => 'required'
        ]);


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('slider'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename= "";
            }

            $feature = "/slider/".$filename;

            $userid = auth()->user()->id;
            $data = new Slider();
            $data->user_id =$userid;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->image = $feature;
            $data->save();

            return redirect()
                ->route('dashboard.setting')
                ->with('message', 'Data berhasil disimpan.');
        }
    }

    public function updateslider(Request $request,$id){
        $validator =  Validator::make($request->all(), [
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'description' => 'nullable',
            'title' => 'nullable'
        ]);


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('slider'), $filename);

                // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
            }else{
                $filename= "";
            }

            $feature = "/slider/".$filename;

            $userid = auth()->user()->id;
            $data = Slider::find($id);
            $data->user_id =$userid;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->image = $feature;
            $data->save();

            return redirect()
                ->route('dashboard.setting')
                ->with('message', 'Data berhasil disimpan.');
        }
    }

    public function storepopup(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|mimes:png,jpg,jpeg|max:5048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }
    
        $newData = [
            'image' => null,
            'status' => 'active',
            'url' => null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];
    
       
        // If no overlap, process image upload and data saving
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('slider'), $filename);
    
            $newData['image'] = "/slider/" . $filename;
        }
    
        if($request->id){
            $data = Popup::find($request->id);
            if($request->hasFile('image') == null){
                $newData['image'] = $data->image;
            }
        }else{
             // Check for existing record with overlapping dates
            $existingPopup = Popup::where('start_date', '<=', $request->end_date)
            ->where('end_date', '>=', $request->start_date)
            ->first();

            if ($existingPopup) {
                return redirect()->back()->withInput($newData)->with('error', 'The specified date range overlaps with an existing popup. Please choose different dates.');
            }

            $data = new Popup();
        }

        
        $data->image = $newData['image'];
        $data->url = $newData['image'];
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->save();
    
        return redirect()->route('dashboard.setting')->with('message', 'Data berhasil disimpan.');
    
    }

    public function editpopup(string $id)
    {
        
        $datapop = Popup::find($id);
        $countries = get_country_lists();
        $settingExists = Setting::exists();

        if ($settingExists) {
            $setting = Setting::first();
        } else {
            $setting = new Setting;
        }

        $slide = Slider::all();
        $popup = Popup::all();

        return view('admin.setting.form',compact('setting', 'countries','slide','popup','datapop'));

        
    }

    public function destroypopup(string $id)
    {
        $data = Popup::find($id);
        $data->delete();
        return redirect()
        ->route('dashboard.setting')
        ->with('message', 'Data deleted.');
    }
}
