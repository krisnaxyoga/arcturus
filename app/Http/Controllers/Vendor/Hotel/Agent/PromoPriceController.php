<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomHotel;
use App\Models\AgentMarkupSetup;
use App\Models\AgentMarkupDetail;
use App\Models\Vendor;
use App\Models\PromoPrice;
use App\Models\ContractRate;
use App\Models\ContractPrice;
use Illuminate\Support\Facades\Validator;

class PromoPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $data = ContractPrice::where('contract_id',$id)->with('contractrate')->get();
        // dd($data);
        return inertia('Vendor/MenageRoom/ContractRate/PromoPrice/Promo',[
            'data' => $data,
            // 'bardata' => $bardata,
            // 'markup' => $price,
        ]);
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
    public function store(Request $request,$id)
    {
        dd($request->all());
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
