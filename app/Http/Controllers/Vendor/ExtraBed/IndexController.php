<?php

namespace App\Http\Controllers\Vendor\ExtraBed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\ExtrabedPrice;
use App\Models\RoomHotel;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->with('users')->first();
        $data = ExtrabedPrice::where('user_id',$iduser)->get();
        $room = RoomHotel::where('user_id',$iduser)->with('extrabedprice')->get();

        return inertia('Vendor/ExtraBedPrice/Index',[
            'data'=> $data,
            'vendor'=> $vendor,
            'room'=>$room
        ]);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iduser = auth()->user()->id;
        $vendor = Vendor::where('user_id',$iduser)->with('users')->first();
        $data = ExtrabedPrice::where('user_id',$iduser)->get();

        return inertia('Vendor/ExtraBedPrice/Index',[
            'data'=> $data,
            'vendor'=> $vendor
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
