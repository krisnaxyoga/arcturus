<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomHotel;
use App\Models\BarRoom;
use App\Models\BarPrice;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class BarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->with('barroom')->with('room')->get();

        return inertia('Vendor/MenageRoom/BarRoom/Index',[
            'data' => $barprice
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)
            ->with('barroom')
            ->with('room')
            ->get();
        $barroom = BarRoom::where('user_id',$userid)->get();

        if($barroom->isEmpty()){
            $data = RoomHotel::where('user_id',$userid)->get();
            return inertia('Vendor/MenageRoom/BarRoom/Create',[
                'data'=>$data
            ]);
        }else{
            return inertia('Vendor/MenageRoom/BarRoom/Edit',[
                'data' => $barprice,
            ]);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
         $validator = Validator::make($request->all(), [
            'barcode' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
                $id = auth()->user()->id;

                $vendor = Vendor::where('user_id',$id)->get();
                $data =  new BarRoom();
                $data->user_id = $id;
                $data->vendor_id = $vendor[0]->id;

                if(!$request->bardesc){
                    $data->barcode = 'BAR';
                    $data->bardesc = 'HOTEL BEST AVAILABLE RATE';
                }else{
                    $data->barcode = $request->barcode;
                    $data->bardesc = $request->bardesc;
                }
                $data->begindate = $request->begin;
                $data->enddate = $request->end;
                $data->save();

                if(!$request->price){
                    return redirect()->back()->with('success', 'Please first fill in your Room Type on the room types menu.');
                }else{
                    foreach($request->price as $item){
                        $bar =  new BarPrice();
                        $bar->user_id = $id;
                        $bar->bar_id = $data->id;
                        $bar->room_id = $item['room_id'];
                        $bar->price = $item['price'];
                        $bar->save();
                    }
                    return redirect()
                    ->route('contract.index')
                    ->with('success', 'Data saved!');
                }
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
        $userid = auth()->user()->id;
        $barprice = BarPrice::where('user_id',$userid)->where('bar_id',$id)->with('barroom')->with('room')->get();
        return inertia('Vendor/MenageRoom/BarRoom/Edit',[
            'data' => $barprice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validator = Validator::make($request->all(), [
            'barcode' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {

                $data = BarRoom::find($id);
                $data->barcode = $request->barcode;
                $data->bardesc = $request->bardesc;
                $data->begindate = $request->begin;
                $data->enddate = $request->end;
                $data->save();

            return redirect()
            ->route('contract.index')
            ->with('success', 'Data saved!');
        }
    }

    public function barupdate(Request $request, string $id)
    {
         $validator = Validator::make($request->all(), [
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        } else {
            $bar = BarPrice::find($id);
            $bar->price = $request->price;
            $bar->save();

        }
        return redirect()
        ->route('contract.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function bardestroy($id){
        $data = BarPrice::find($id);
        $data->delete();
        return redirect()->back()->with('message', 'Data deleted!');
    }

    public function cekroom($barid){
        $userid = auth()->user()->id;
        $data = RoomHotel::where('user_id',$userid)->get();
        foreach($data as $item){
            $barpriceExists = BarPrice::where('room_id', $item->id)->exists();
            if(!$barpriceExists){
                $bar =  new BarPrice();
                $bar->user_id = $userid;
                $bar->bar_id = $barid;
                $bar->room_id = $item->id;
                $bar->save();
            }
        }
        return redirect()
        ->route('contract.index')
        ->with('success', 'Room Add Success!');
    }

}
