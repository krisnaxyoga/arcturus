<?php

namespace App\Http\Controllers\Vendor\Hotel\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use App\Models\RoomHotel;

use App\Models\BarPrice;

use App\Models\ContractPrice;
// use App\Models\AdultPriceRoom;
use App\Models\Roomtype;
use App\Models\BarRoom;
use App\Models\AttributeRoom;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->with('users')->first();
        $data = RoomHotel::where('user_id',$iduser)->get();
        // $child = ChildPriceRoom::with('roomhotel')->get();
        // $adult = AdultPriceRoom::with('roomhotel')->get();
        return inertia('Vendor/MenageRoom/Index',[
            'data'=>$data,
            'vendor' => $vendor
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id = auth()->user()->id;
        $attr = AttributeRoom::where('user_id',1)->get();
        $roomtype = Roomtype::all();
        $vendor = Vendor::where('user_id',$id)->with('users')->first();

        return inertia('Vendor/MenageRoom/CreateRoom',[
            'attr' => $attr,
            'roomtype' => $roomtype,
            'vendor'=>$vendor
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->maxadult);
        $validator = Validator::make($request->all(), [
            'roomname' => 'nullable',
            // 'price' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $galleryPaths = [];

                if($request->hasFile('gallery')){
                    foreach ($request->file('gallery') as $gallery) {
                        $filename = uniqid().'.'.$gallery->getClientOriginalExtension();
                        // $gallery->move(public_path('room'), $filename);
                        $compressedImage = Image::make($gallery->getRealPath());
                        $compressedImage->resize(1500, 1000)->save(public_path('room/' . $filename), 90); // 90 adalah kualitas kompresi yang lebih baik
                        // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database

                        $galleryPaths[] = "/room/".$filename;
                    }
                }else{
                    $galleryPaths = [];
                }

                if ($request->hasFile('feature_image')) {
                    $feature_image = $request->file('feature_image');
                    $filename = time() . '.' . $feature_image->getClientOriginalExtension();
                    $compressedImage = Image::make($feature_image->getRealPath());
                    $compressedImage->resize(1500, 1000)->save(public_path('feature/room/' . $filename), 90); // 90 adalah kualitas kompresi yang lebih baik
                    // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
                }else{
                    $filename= "";
                }
                $iduser = auth()->user()->id;
                // dd($request->description);
                //create post
                $feature = "/feature/room/".$filename;
                // dd($feature);

                $vendor = Vendor::where('user_id',$iduser)->get();
                // dd($vendor[0]->id);
                $room = new RoomHotel();
                $room->user_id = $iduser;
                $room->roomtype_id = 0;//$request->roomtypeid;
                $room->vendor_id = $vendor[0]->id;
                $room->title = $request->roomname;
                // $room->video = $request->video;
                $room->gallery = $galleryPaths;
                $room->content = $request->content;
                $room->ratecode = $request->ratecode;
                $room->ratedesc = $request->ratedesc;
                // $room->extrabed = $request->extrabed;
                $room->max_adult = $request->maxadult;
                $room->max_child = $request->maxchild;
                $room->size= $request->size;
                $room->adults = $request->adult;
                // $room->children = $request->child;
                $room->children = 0;
                // $room->infant = $request->infant;

                $room->infant = 0;
                $room->extra_bed = $request->extra_bed;
                // $room->baby_cot = $request->baby_cot;
                $room->baby_cot = 0;
                $room->bedroom = $request->bedroom;
                $room->max_benefit = $request->max_benefit;

                // $room->min_night = $request->night;
                // $room->beginsaledate = $request->begindate;
                // $room->endsaledate = $request->enddate;
                $room->	room_allow = $request->allowed;
                $room->nearby_info = $request->near;
                $room->service_info = $request->service;
                $room->attribute = $request->facilities;
                $room->feature_image = $feature;
                // $room->price = $request->price;
                // $room->	week_sell = json_encode($request->week_sale);
                $room->save();

                $barroom = BarRoom::where('user_id',$iduser)->get();

                if(!$barroom->isEmpty()){
                    $bar =  new BarPrice();
                    $bar->user_id = $iduser;
                    $bar->bar_id = $barroom[0]->id;
                    $bar->room_id = $room->id;
                    $bar->save();
                }

                return redirect()
                ->route('vendor.room.edit',$room->id)
                ->with('success', 'Data saved!');
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
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->with('users')->first();
        $attr = AttributeRoom::where('user_id',1)->get();
        $room = RoomHotel::query()->where('user_id',$iduser)->where('id',$id)->get();
        $roomtype = Roomtype::all();
        return inertia('Vendor/MenageRoom/EditRoom',[
            'room' => $room,
            'attr' => $attr,
            'roomtype' => $roomtype,
            'vendor' => $vendor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // dd($request->maxadult);
        $validator = Validator::make($request->all(), [
            'roomname' => 'nullable',
            // 'price' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $room = RoomHotel::find($id);

                $galleryPaths = [];

                if($request->hasFile('gallery')){
                    foreach ($request->file('gallery') as $gallery) {
                        $filename = uniqid().'.'.$gallery->getClientOriginalExtension();
                        // $gallery->move(public_path('room'), $filename);
                        $compressedImage = Image::make($gallery->getRealPath());
                        $compressedImage->resize(1500, 1000)->save(public_path('room/' . $filename), 90); // 90 adalah kualitas kompresi yang lebih baik
                        // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database

                        $galleryPaths[] = "/room/".$filename;
                    }
                }else{
                    $galleryPaths = $room->gallery;
                }

                if ($request->hasFile('feature_image')) {
                    $feature_image = $request->file('feature_image');
                    $filename = time() . '.' . $feature_image->getClientOriginalExtension();
                    // $feature_image->move(public_path('feature/room'), $filename);
                    $compressedImage = Image::make($feature_image->getRealPath());
                    $compressedImage->resize(1500, 1000)->save(public_path('feature/room/' . $filename), 90); // 90 adalah kualitas kompresi yang lebih baik
                    // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database

                    $feature = "/feature/room/".$filename;
                }else{
                    $feature = $room->feature_image;
                }
                $iduser = auth()->user()->id;

                $room->roomtype_id = 0;//$request->roomtypeid;
                $room->title = $request->roomname;
                // $room->video = $request->video;
                $room->room_allow = $request->allowed;
                $room->gallery = $galleryPaths;
                $room->content = $request->content;
                $room->ratecode = $request->ratecode;
                $room->ratedesc = $request->ratedesc;
                // $room->extrabed = $request->extrabed;
                $room->max_adult = $request->maxadult;
                $room->max_child = $request->maxchild;
                $room->size = $request->size;
                $room->adults = $request->adult;
                // $room->children = $request->child;
                // $room->infant = $request->infant;
                $room->children = 0;
                $room->infant = 0;
                $room->extra_bed = $request->extra_bed;
                // $room->baby_cot = $request->baby_cot;
                // $room->bedroom = $request->bedroom;
                $room->baby_cot = 0;
                // $room->max_benefit = $request->max_benefit;

                // $room->min_night = $request->night;
                // $room->beginsaledate = $request->begindate;
                // $room->endsaledate = $request->enddate;
                //$room->	room_allow = $request->allowed;
                $room->nearby_info = $request->near;
                $room->service_info = $request->service;
                // dd($request->facilities);
                $room->attribute = explode(",", $request->facilities);
                // $room->attribute = $request->facilities;
                $room->feature_image = $feature;
                // $room->price = $request->price;
                // $room->	week_sell = json_encode($request->week_sale);
                $room->save();

                return redirect()
                ->route('vendor.room')
                ->with('success', 'Data saved!');
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $iduser = auth()->user()->id;

        $room = RoomHotel::find($id);

        if ($room) {
            if (File::exists(public_path($room->feature_image)))
            {
                File::delete(public_path($room->feature_image));
            }
            foreach($room->gallery as $gallery){
                if (File::exists(public_path($gallery)))
                {
                    File::delete(public_path($gallery));
                }
            }
            $room->delete();
        }

        $barprice = BarPrice::where('user_id', $iduser)->where('room_id', $id)->first();

        if ($barprice) {
            $barprice->delete();
        }

        $contractprice = ContractPrice::where('user_id', $iduser)->where('room_id', $id)->get();

        if ($contractprice) {
            foreach($contractprice as $contprice){
                $contprice->delete();
            }
        }

        return redirect()->back()->with('message', 'Data deleted!');
    }
}
