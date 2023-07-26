<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\ContractRate;
use App\Models\ContractPrice;
use App\Models\RoomHotel;
use App\Models\User;
use App\Models\AgentMarkupDetail;
use App\Models\AgentMarkupSetup;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('landingpage.index');
    }

    public function hotel(Request $request)
    {
        $vendor = ContractPrice::whereHas('contractrate.vendors', function ($query) use ($request) {
            $query->where('type_vendor', 'hotel')
                ->where('is_active', 1);
        
            $query->when($request->country, function ($q, $country) {
                return $q->where('country', $country);
            });
        
            $query->when($request->state, function ($q, $state) {
                return $q->where('state', $state);
            });
        
            $query->when($request->city, function ($q, $city) {
                return $q->where('city', $city);
            });
        
            $query->when($request->country && $request->state && $request->city, function ($q) use ($request) {
                return $q->tap(function ($subquery) use ($request) {
                    $subquery->where('country', $request->country)
                        ->where('state', $request->state)
                        ->where('city', $request->city);
                });
            });
        })
        ->whereHas('room', function ($query) use ($request) {
            $query->when($request->person, function ($q, $person) {
                return $q->where('adults', '>=', $person);
            });
        })
        ->with('contractrate.vendors')
        ->with('room')
        ->whereIn('contract_prices.id', function ($subquery) {
            $subquery->select(\DB::raw('MIN(contract_prices.id)'))
                ->from('contract_prices')
                ->groupBy('contract_prices.contract_id');
        })
        ->paginate(6);

        $vendorIds = $vendor->pluck('contractrate.vendor_id')->toArray();

        $cekdate = AgentMarkupDetail::whereIn('vendor_id', $vendorIds)->where('markup_cat_id','blackout')->get();

        $today = now()->format('Y-m-d');

        foreach ($cekdate as $detail) {
            $vendorId = $detail->vendor_id;
            $startDate = $detail->start_date;
            $endDate = $detail->end_date;

            if ($today == $startDate) {
                $venorupdate = Vendor::find($vendorId);
                $venorupdate->is_active = 0;
                $venorupdate->save();
            } elseif ($today > $endDate) {
                $venorupdate = Vendor::find($vendorId);
                $venorupdate->is_active = 1;
                $venorupdate->save();
            }
        }

        $data = $vendor;
       
        return view('landingpage.hotel.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function hoteldetail(Request $request,$id)
    {
        
        $category = $request->input('data.category');

        if ($category) {
            $vendor = ContractPrice::whereHas('room', function ($query) use ($category) {
                    $query->where('id', $category);
                })
                ->where('contract_id', $id)
                ->with('contractrate')
                ->with('contractrate.vendors')
                ->with('room')
                ->get();
        } else {
            $vendor = ContractPrice::where('contract_id', $id)
                ->with('contractrate')
                ->with('contractrate.vendors')
                ->with('room')
                ->get();
        }
        

        $data = $vendor;

        $vendordetail = Vendor::where('id',$vendor[0]->contractrate->vendor_id)->first();

        $service = AgentMarkupSetup::where('vendor_id',$vendor[0]->contractrate->vendor_id)->first();

        $roomtype = RoomHotel::where('vendor_id',$vendor[0]->contractrate->vendor_id)->get();

        return view('landingpage.hotel.detail',compact('data','roomtype','service','vendordetail'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function about()
    {
        return view('landingpage.about');
    }
    public function contact()
    {
        return view('landingpage.contact');
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
