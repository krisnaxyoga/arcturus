<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\ContractRate;
use App\Models\ContractPrice;
use App\Models\RoomHotel;
use App\Models\User;
use App\Models\Slider;
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

        if(auth()->check()){

            $iduser= auth()->user()->id;
            $user = User::where('id',$iduser)->first();

            if($user->role_id == 2){

                $slider = Slider::where('user_id',$iduser)->get();
            }else{
                $slider = Slider::where('user_id',1)->get();
                // $slider = Slider::all();
            }

        }else{
            $slider = Slider::where('user_id',1)->get();
            // $slider = Slider::all();
        }

        $hotel = Vendor::where('type_vendor','hotel')->count();
        $agent = Vendor::where('type_vendor','agent')->count();

        return view('landingpage.index',compact('slider','hotel','agent'));
    }

    public function hotel(Request $request)
    {
        $checkin = Carbon::parse($request->checkin);
        $checkout = Carbon::parse($request->checkout);
        $Nights = $checkout->diffInDays($checkin);

        if (isset($request->checkin) && isset($request->checkout)) {
            $inputCheckin = $request->checkin;
            $inputCheckout = $request->checkout;

            $checkin1 = Carbon::createFromFormat('Y-m-d', $inputCheckin);
            $checkout2 = Carbon::createFromFormat('Y-m-d', $inputCheckout);

            if ($checkout2->lt($checkin1)) {
                // Menghitung selisih hari antara checkin dan checkout
                $dayDifference = $checkin1->diffInDays($checkout2);

                // Menggunakan selisih hari untuk menambahkan ke checkout
                $checkout2 = $checkin1->copy()->addDays($dayDifference);
            }

            // Menyimpan nilai $checkin dan $checkout pada $datareq
            $request->checkin = $checkin1->format('Y-m-d');
            $request->checkout = $checkout2->format('Y-m-d');
        }
        // dd($totalNights);
        $contractrateMaxMinStay = ContractRate::max('min_stay');

        if ($contractrateMaxMinStay < $Nights) {
            // $totalNights = $contractrateMaxMinStay;
            $totalNights = 1;
        }else{
            $totalNights = $Nights;
            // dd($totalNights);
        }

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
        ->whereHas('contractrate', function ($query) use ($totalNights) {
            $query->where(function ($subquery) use ($totalNights) {
                $subquery->where('min_stay', '=', 1);
            });
            
        })
        // ->whereHas('contractrate', function ($query) use ($totalNights) {
        //     $minstay = ContractRate::where('min_stay', '<=', $totalNights)
        //         ->orderBy('min_stay', 'desc')
        //         ->first();

        //     if ($minstay) {
        //         $totalNights = $minstay->min_stay;
        //     }

        //     $query->where(function ($subquery) use ($totalNights) {
        //         $subquery->where('min_stay', '>=', $totalNights);
        //         // Tambahan: Kondisi stay period
        //         $subquery->whereDate('stayperiod_begin', '<=', now())
        //         ->whereDate('stayperiod_end', '>=', now());
        //     });
        //     // $query->where('rolerate', 1);
        // })
        // ->whereHas('room', function ($query) use ($request) {
        //     $query->when($request->person, function ($q, $person) {
        //         return $q->where('adults', '>=', $person);
        //     });
        // })
        ->whereHas('room', function ($query) use ($request) {
            $query->when($request->person, function ($q, $person) {
                return $q->where(function ($qq) use ($person) {
                    $qq->where('adults', '>=', $person)
                       ->orWhere(function ($qqq) use ($person) {
                           $qqq->where('adults', '>=', $person - 1)
                                ->where('extra_bed', '>', 0);
                       });
                });
            });
        })
        ->with('contractrate.vendors')
        ->with('room')
        ->whereIn('contract_prices.id', function ($subquery) {
            $subquery->select(\DB::raw('MIN(contract_prices.id)'))
                ->from('contract_prices')
                ->groupBy('contract_prices.contract_id');
        })->paginate(6);

        // $vendorIds = $vendor->pluck('contractrate.vendor_id')->toArray();


        // $blackoutVendorIds = AgentMarkupDetail::where('vendor_id', $vendorIds)
        //         ->where('markup_cat_id', 'blackout')
        //         ->where(function ($q) use ($checkin, $checkout) {
        //             $q->where(function ($qq) use ($checkin, $checkout) {
        //                 $qq->where('start_date', '<=', $checkout)
        //                     ->where('end_date', '>=', $checkin)
        //                     ->where('markup_cat_id', 'blackout');
        //             });
        //         })
        //         ->orWhere(function ($q) use ($checkin, $checkout,$vendorIds) {
        //             $q->where('start_date', '<=', $checkout)
        //                 ->where('end_date', '>=', $checkout)
        //                 ->where('markup_cat_id', 'blackout')
        //                 ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout,$vendorIds) {
        //                     $query->select('vendor_id')
        //                         ->from('agent_markup_details')
        //                         ->where('vendor_id', $vendorIds)
        //                         ->where('markup_cat_id', 'blackout')
        //                         ->where('start_date', '<=', $checkout)
        //                         ->where('end_date', '>=', $checkin);
        //                 });
        //         })
        //     ->pluck('vendor_id');

        // $surchargesDetail = AgentMarkupDetail::where('vendor_id', $vendorIds)
        //     ->where('markup_cat_id', 'surcharges')
        //     ->where(function ($q) use ($checkin, $checkout) {
        //         $q->where(function ($qq) use ($checkin) {
        //             $qq->where('start_date', '<=', $checkin)
        //                 ->where('end_date', '>=', $checkin);
        //         });
        //     })
        //     ->orWhere(function ($q) use ($checkin, $checkout,$vendorIds) {
        //         $q->where(function ($qq) use ($checkin, $checkout,$vendorIds) {
        //             $qq->where('start_date', '<=', $checkout->subDay())
        //                 ->where('end_date', '>=', $checkout->subDay())
        //                 ->where('vendor_id', $vendorIds)
        //                 ->whereNotIn('vendor_id', function ($query) use ($checkin) {
        //                     $query->select('vendor_id')
        //                         ->from('agent_markup_details')
        //                         ->where('markup_cat_id', 'surcharges')
        //                         ->where('start_date', '<=', $checkin)
        //                         ->where('end_date', '>=', $checkin);
        //                 });
        //         })->orWhere(function ($qq) use ($checkin, $checkout) {
        //             $qq->where('start_date', '<=', $checkout)
        //                 ->where('end_date', '>=', $checkout)
        //                 ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
        //                     $query->select('vendor_id')
        //                         ->from('agent_markup_details')
        //                         ->where('markup_cat_id','=', 'surcharges')
        //                         ->where('start_date', '<=', $checkout)
        //                         ->where('end_date', '>=', $checkin);
        //                 });
        //         });
        //     })
        //     ->pluck('vendor_id');

        //     if ($surchargesDetail->isNotEmpty()) {
        //             $surchargesprice = AgentMarkupDetail::where('vendor_id', $vendorIds)
        //                 ->where('markup_cat_id', 'surcharges')
        //                 ->where(function ($q) use ($checkin, $checkout) {
        //                     $q->where(function ($qq) use ($checkin, $checkout) {
        //                         $qq->where('start_date', '<=', $checkout->subDay())
        //                             ->where('end_date', '>=', $checkin);
        //                     });
        //                 })
        //                 ->orWhere(function ($q) use ($checkin, $checkout) {
        //                     $q->where('start_date', '<=', $checkout)
        //                         ->where('end_date', '>=', $checkout)
        //                         ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
        //                             $query->select('vendor_id')
        //                                 ->from('agent_markup_details')
        //                                 ->where('markup_cat_id', 'surcharges')
        //                                 ->where('start_date', '<=', $checkout)
        //                                 ->where('end_date', '>=', $checkin);
        //                         });
        //                 })
        //                 ->first();
        //         $surchargesVendorIds = $surchargesDetail; // Mengisi koleksi dengan daftar vendor_id
        //         $surcharprice = $surchargesprice->surcharge_block_price;

            // }else{
            //     $surcharprice = 0;
            // }

        $data = $vendor;

        $requestdata = [
            'country'=>$request->country,
            'state'=>$request->state,
            'person'=>$request->person,
            'checkin'=>$request->checkin,
            'checkout'=>$request->checkout
        ];

        $data->appends($requestdata);

        // return view('landingpage.hotel.index',compact('data','requestdata','blackoutVendorIds','surchargesDetail','surcharprice'));

        return view('landingpage.hotel.index',compact('data','requestdata'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function hoteldetail(Request $request,$id)
    {

        $category = $request->input('data.category');
        $datareq = $request->all();
        // dd($datareq['checkin']);\

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

        $contractprice = ContractPrice::where('user_id', $vendor[0]->user_id)
            ->with('contractrate')
            ->with('contractrate.vendors')
            ->with('room')
            ->whereHas('contractrate', function ($query) {
                $query->where('rolerate', '=', 2);
            })
            ->get();

        $slider = Slider::where('user_id',$vendor[0]->user_id)->get();

        $inputCheckin1 = $datareq['checkin'];
        $inputCheckout1 = $datareq['checkout'];
        $checkin2 = Carbon::createFromFormat('Y-m-d', $inputCheckin1);
        $checkout2 = Carbon::createFromFormat('Y-m-d', $inputCheckout1);
        $Nights = $checkout2->diffInDays($checkin2);

        if (isset($datareq['checkin']) && isset($datareq['checkout'])) {
            $inputCheckin = $datareq['checkin'];
            $inputCheckout = $datareq['checkout'];

            $checkin = Carbon::createFromFormat('Y-m-d', $inputCheckin);
            $checkout = Carbon::createFromFormat('Y-m-d', $inputCheckout);

            if ($checkout->lt($checkin)) {
                // Menghitung selisih hari antara checkin dan checkout
                $dayDifference = $checkin->diffInDays($checkout);

                // Menggunakan selisih hari untuk menambahkan ke checkout
                $checkout = $checkin->copy()->addDays($dayDifference);
            }

            // Menyimpan nilai $checkin dan $checkout pada $datareq
            $datareq['checkin'] = $checkin->format('Y-m-d');
            $datareq['checkout'] = $checkout->format('Y-m-d');
        }

        $vendordetail = Vendor::where('id',$vendor[0]->contractrate->vendor_id)->first();

        $service = AgentMarkupDetail::where('vendor_id',$vendor[0]->contractrate->vendor_id)->get();

        $roomtype = RoomHotel::where('vendor_id',$vendor[0]->contractrate->vendor_id)->get();

        // $surcharprice = 0;

        // $surchargesVendorIds = 0;
        // $blackoutVendorIds = 0;

        if (isset($datareq)) {
            // Lakukan sesuatu dengan $datareq

            $checkin = $datareq['checkin'];
            $checkout = $datareq['checkout'];

            $vendorIds = [$vendor[0]->contractrate->vendor_id];
            // $surchargesDetail = AgentMarkupDetail::where('vendor_id', $vendorIds)->get();

            // $surchargesDetail = AgentMarkupDetail::where('vendor_id', $vendorIds)
            //     ->where('markup_cat_id', 'surcharges')
            //     ->where(function ($q) use ($checkin, $checkout) {
            //         $q->where(function ($qq) use ($checkin) {
            //             $qq->where('start_date', '<=', $checkin)
            //                 ->where('end_date', '>=', $checkin);
            //         });
            //     })
            //     ->orWhere(function ($q) use ($checkin, $checkout,$vendorIds) {
            //         $q->where(function ($qq) use ($checkin, $checkout,$vendorIds) {
            //             $qq->where('start_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
            //                 ->where('end_date', '>=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
            //                 ->where('vendor_id', $vendorIds)
            //                 ->whereNotIn('vendor_id', function ($query) use ($checkin) {
            //                     $query->select('vendor_id')
            //                         ->from('agent_markup_details')
            //                         ->where('markup_cat_id', 'surcharges')
            //                         ->where('start_date', '<=', $checkin)
            //                         ->where('end_date', '>=', $checkin);
            //                 });
            //         })->orWhere(function ($qq) use ($checkin, $checkout) {
            //             $qq->where('start_date', '<=', $checkout)
            //                 ->where('end_date', '>=', $checkout)
            //                 ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
            //                     $query->select('vendor_id')
            //                         ->from('agent_markup_details')
            //                         ->where('markup_cat_id','=', 'surcharges')
            //                         ->where('start_date', '<=', $checkout)
            //                         ->where('end_date', '>=', $checkin);
            //                 });
            //         });
            //     })
            //     // ->get();
            //     ->pluck('vendor_id');

            //     $surchargesprice = AgentMarkupDetail::where('vendor_id', $vendorIds)
            //         ->where("markup_cat_id", "surcharges")
            //         ->where('markup_cat_id', '=', 'surcharges')
            //         ->where(function ($q) use ($checkin, $checkout) {
            //             $q->where(function ($qq) use ($checkin, $checkout) {
            //                 $qq->where('start_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
            //                     ->where('end_date', '>=', $checkin)
            //                     ->where('markup_cat_id', '=', 'surcharges');
            //             });
            //         })
            //         ->orWhere(function ($q) use ($checkin, $checkout,$vendorIds) {
            //             $q->where('start_date', '<=', $checkout)
            //                 ->where('end_date', '>=', $checkout)
            //                 ->where('markup_cat_id', '=', 'surcharges')
            //                 ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout,$vendorIds) {
            //                     $query->select('vendor_id')
            //                         ->from('agent_markup_details')
            //                         ->where('vendor_id', $vendorIds)
            //                         ->where("markup_cat_id", "surcharges")
            //                         ->where('start_date', '<=', $checkout)
            //                         ->where('end_date', '>=', $checkin);
            //                 });
            //         })
            //         ->first();

                // dd($surchargesprice);

                // $blackoutVendorIds = AgentMarkupDetail::where('vendor_id', $vendorIds)
                //     ->where('markup_cat_id', 'blackout')
                //     ->where(function ($q) use ($checkin, $checkout) {
                //         $q->where(function ($qq) use ($checkin, $checkout) {
                //             $qq->where('start_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
                //                 ->where('end_date', '>=', $checkin)
                //                 ->where('markup_cat_id', 'blackout');
                //         });
                //     })
                //     ->orWhere(function ($q) use ($checkin, $checkout) {
                //         $q->where('start_date', '<=', $checkout)
                //             ->where('end_date', '>=', $checkout)
                //             ->where('markup_cat_id', 'blackout')
                //             ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
                //                 $query->select('vendor_id')
                //                     ->from('agent_markup_details')
                //                     ->where('markup_cat_id', 'blackout')
                //                     ->where('start_date', '<=', $checkout)
                //                     ->where('end_date', '>=', $checkin);
                //             });
                //     })
                //     ->pluck('vendor_id');
                    // dd($blackoutVendorIds);

        //     if ($surchargesDetail->isNotEmpty() ) {
        //         $surchargesVendorIds = $surchargesDetail; // Mengisi koleksi dengan daftar vendor_id

        //         $surcharprice = $surchargesprice->surcharge_block_price;

        //     }else{
        //         $surcharprice = 0;
        //     }
        // } else {
        //     $surchargesVendorIds = 0;
        //     $blackoutVendorIds = 0;
        }

        $data = $vendor;
        // return view('landingpage.hotel.detail',compact('data','roomtype','service','vendordetail','datareq','surcharprice','surchargesVendorIds','blackoutVendorIds'));

        return view('landingpage.hotel.detail',compact('data','slider','Nights','roomtype','service','vendordetail','datareq','contractprice'));
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
