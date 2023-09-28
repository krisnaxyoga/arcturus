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
use App\Models\HotelRoomBooking;
use App\Models\HotelRoomSurcharge;
use App\Models\AdvancePurchase;
use App\Models\AdvancePurchasePrice;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (auth()->check()) {

            $iduser = auth()->user()->id;
            $user = User::where('id', $iduser)->first();

            if ($user->role_id == 2) {

                $slider = Slider::where('user_id', $iduser)->get();
            } else {
                // $slider = Slider::where('user_id', 1)->get();
                $slider = Slider::all();
            }
        } else {
            // $slider = Slider::where('user_id', 1)->get();
            $slider = Slider::all();
        }

        $hotel = Vendor::where('type_vendor', 'hotel')->count();
        $agent = Vendor::where('type_vendor', 'agent')->count();

        return view('landingpage.index', compact('slider', 'hotel', 'agent'));
    }

    public function hotel(Request $request)
    {
        
        $checkin = Carbon::parse($request->checkin);
        $checkout = Carbon::parse($request->checkout);
        $Nights = $checkout->diffInDays($checkin);
        $today = Carbon::now();

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
        } else {
            $totalNights = $Nights;
            // dd($totalNights);
        }

        
        $selectedProperties = $request->input('properties', []);

        $vendor = ContractPrice::whereHas('contractrate.vendors', function ($query) use ($request,$selectedProperties) {
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

            $query->when(!empty($selectedProperties), function ($q) use ($selectedProperties) {
                if (is_string($selectedProperties)) {
                    // Ubah string menjadi array jika diperlukan
                    $selectedProperties = explode(',', $selectedProperties);
                }
                return $q->whereIn('type_property', $selectedProperties);
            });            

            $query->when($request->country && $request->state && $request->city && $selectedProperties, function ($q) use ($request,$selectedProperties) {
                return $q->tap(function ($subquery) use ($request) {
                    $subquery->where('country', $request->country)
                        ->where('state', $request->state)
                        ->where('city', $request->city)
                        ->whereIn('type_property', $selectedProperties);
                });
            });
        })
        ->whereHas('contractrate', function ($query) {
                $query->where('rolerate', 1);
            })
        
            ->whereHas('contractrate', function ($query) use ($checkin, $checkout) {
                $query->where(function ($q) use ($checkin, $checkout) {
                    $q->where(function ($qq) use ($checkin, $checkout) {
                        $qq->where('stayperiod_begin', '<=', $checkin)
                            ->where('stayperiod_end', '>=', $checkout);
                    })->Where(function ($qq) use ($checkin, $checkout) {
                        $qq->where('booking_begin', '<=', $checkin)
                            ->where('booking_end', '>=', $checkout);
                    });
                });
            })
            ->where('is_active',1)
            ->with('contractrate.vendors')
            ->with('room');
            
            $contractprice = ContractPrice::whereHas('contractrate.vendors', function ($query) use ($request, $selectedProperties) {
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
            
                $query->when(!empty($selectedProperties), function ($q) use ($selectedProperties) {
                    if (is_string($selectedProperties)) {
                        // Convert string to an array if needed
                        $selectedProperties = explode(',', $selectedProperties);
                    }
                    return $q->whereIn('type_property', $selectedProperties);
                });
            
                $query->when($request->country && $request->state && $request->city && $selectedProperties, function ($q) use ($request, $selectedProperties) {
                    return $q->tap(function ($subquery) use ($request) {
                        $subquery->where('country', $request->country)
                            ->where('state', $request->state)
                            ->where('city', $request->city)
                            ->whereIn('type_property', $selectedProperties);
                    });
                });
            })
            ->whereHas('contractrate', function ($query) use ($checkin, $checkout) {
                $query->where(function ($q) use ($checkin, $checkout) {
                    $q->where(function ($qq) use ($checkin, $checkout) {
                        $qq->where('stayperiod_begin', '<=', $checkin)
                            ->where('stayperiod_end', '>=', $checkout);
                    })->orWhere(function ($qq) use ($checkin, $checkout) {
                        $qq->where('booking_begin', '<=', $checkin)
                            ->where('booking_end', '>=', $checkout);
                    });
                });
            })
            ->where('is_active',1)
            ->with('contractrate.vendors')
            ->with('room')
            // ->groupBy('contract_prices.id', 'contract_id','user_id') // Tambahkan semua kolom yang tidak diagregat
            ->get();

            // dd($checkin);
            $interval = $today->diffInDays($checkin);
            $day = $interval + 1;
            // dd($day);
            $advancepurchase = AdvancePurchasePrice::whereHas('advancepurchase', function ($query) use ($day, $checkin, $checkout) {
                $query->where('is_active', 1)
                    ->where(function ($query) use ($checkin,$checkout) {
                        $query->whereDate('beginsell', '<=', $checkin)
                              ->whereDate('endsell', '>=', $checkin);
                    })
                    ->where(function ($query) use ($day) {
                        $query->where('day', '<=', $day)
                              ->orWhereNull('day');
                    });
            })
            ->with('room')
            ->with('users')
            ->with('advancepurchase')
            ->get();

        $data = $vendor;

        if ($request->sort == 'low_to_high') {
            $data = $data->whereIn('contract_prices.id', function ($subquery) {
                $subquery->select(\DB::raw('MIN(contract_prices.id)'))
                    ->from('contract_prices')
                    ->groupBy('contract_prices.contract_id');
            })->orderBy('contract_prices.recom_price', 'ASC');
        } elseif ($request->sort == 'high_to_low') {
            $data = $data->whereIn('contract_prices.id', function ($subquery) {
                $subquery->select(\DB::raw('MIN(contract_prices.id)'))
                    ->from('contract_prices')
                    ->groupBy('contract_prices.contract_id');
            })->orderBy('contract_prices.recom_price', 'DESC');
        }else{
            $data = $data->whereIn('contract_prices.id', function ($subquery) {
                $subquery->select(\DB::raw('MIN(contract_prices.id)'))
                    ->from('contract_prices')
                    ->groupBy('contract_prices.contract_id');
            });
        }

        // Eksekusi query dan terapkan paginasi
        $data = $data->paginate(6);

        // Buat array data request
        $requestdata = [
            'country' => $request->country,
            'state' => $request->state,
            'person' => $request->person,
            'checkin' => $request->checkin,
            'checkout' => $request->checkout,
            'sort' => $request->sort,
            'properties' =>$selectedProperties
        ];

        // Terapkan append ke objek paginasi
        $data->appends($requestdata);

        // return view('landingpage.hotel.index',compact('data','requestdata','blackoutVendorIds','surchargesDetail','surcharprice'));

        return view('landingpage.hotel.index', compact('data', 'requestdata','contractprice','advancepurchase'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function hoteldetail(Request $request, $id)
    {
        $iduser = auth()->user()->id;
        $agent_country = Vendor::where('user_id', $iduser)->first();
        $agentCountry = $agent_country->country;
        $category = $request->input('data.category');
        $datareq = $request->all();
        // dd($datareq['checkin']);

        $inputCheckin1 = $datareq['checkin'];
        $inputCheckout1 = $datareq['checkout'];
        $checkin2 = Carbon::createFromFormat('Y-m-d', $inputCheckin1);
        $checkout2 = Carbon::createFromFormat('Y-m-d', $inputCheckout1);
        $Nights = $checkout2->diffInDays($checkin2);

        $contract_hotel = ContractRate::where('id', $id)->first();

            $vendor = ContractPrice::where('contract_id', $id)
                ->where('is_active',1)
                ->with('contractrate')
                ->with('contractrate.vendors')
                ->with('room')
                ->whereHas('contractrate', function ($query) use ($agentCountry,$Nights) {
                    $query->where('distribute', 'LIKE', '%' . $agentCountry . '%')
                        ->orWhere('distribute', 'LIKE', '%all%')
                        ->orWhere('distribute', 'LIKE', '%WORLDWIDE%');

                    $query->where('min_stay', $Nights)
                    ->orWhere('min_stay', 1);
                })
                ->orderBy('recom_price', 'asc')
                ->get();
        // }

    //    dd($vendor);

        $slider = Slider::where('user_id', $vendor[0]->user_id)->get();

        if (isset($datareq['checkin']) && isset($datareq['checkout'])) {
            $inputCheckin = $datareq['checkin'];
            $inputCheckout = $datareq['checkout'];

            $checkin = Carbon::createFromFormat('Y-m-d', $inputCheckin);
            $checkout = Carbon::createFromFormat('Y-m-d', $inputCheckout);
            $today = Carbon::now();
            

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

        $vendordetail = Vendor::where('id', $vendor[0]->contractrate->vendor_id)->first();

        $service = AgentMarkupDetail::where('vendor_id', $vendor[0]->contractrate->vendor_id)->get();

        $roomtype = RoomHotel::where('vendor_id', $vendor[0]->contractrate->vendor_id)->get();

        // $surcharprice = 0;

        // $surchargesVendorIds = 0;
        // $blackoutVendorIds = 0;

        if (isset($datareq)) {
            // Lakukan sesuatu dengan $datareq

            $checkin = $datareq['checkin'];
            $checkout = $datareq['checkout'];
            
            $vendorIds = [$vendor[0]->contractrate->vendor_id];
         
            $contractprice = ContractPrice::where('user_id', $vendor[0]->user_id)
                ->where('is_active',1)
                ->with('contractrate')
                ->with('contractrate.vendors')
                ->with('room')
                ->whereHas('contractrate', function ($query) use ($agentCountry) {
                    $query->where('distribute', 'LIKE', '%' . $agentCountry . '%')
                    ->orWhere('distribute', 'LIKE', '%all%')
                    ->orWhere('distribute', 'LIKE', '%WORLDWIDE%');
                })
                ->whereHas('contractrate', function ($query) use ($checkin, $checkout) {
                    $query->where(function ($q) use ($checkin, $checkout) {
                        $q->where(function ($qq) use ($checkin, $checkout) {
                            $qq->where('stayperiod_begin', '<=', $checkin)
                                ->where('stayperiod_end', '>=', $checkout);
                        })->Where(function ($qq) use ($checkin, $checkout) {
                            $qq->where('booking_begin', '<=', $checkin)
                                ->where('booking_end', '>=', $checkout);
                        });
                    });
                })
                ->orderBy('recom_price', 'asc')
                ->get();


            $HotelRoomBooking = HotelRoomBooking::where('vendor_id', $vendorIds)
            ->whereHas('booking', function ($query) {
                $query->where('booking_status', 'paid');
            })
            ->where(function ($q) use ($checkin, $checkout) {
                $q->where(function ($qq) use ($checkin, $checkout) {
                    $qq->where('checkin_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
                        ->where('checkout_date', '>=', $checkin);
                });
            })
            ->orWhere(function ($q) use ($checkin, $checkout) {
                $q->where('checkin_date', '<=', $checkout)
                    ->where('checkout_date', '>=', $checkout)
                    ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
                        $query->select('vendor_id')
                            ->from('hotel_room_bookings')
                            ->where('checkin_date', '<=', $checkout)
                            ->where('checkout_date', '>=', $checkin);
                    });
            })
            ->get();

            $HotelCalendar = HotelRoomSurcharge::where('vendor_id', $vendorIds)
            ->where(function ($q) use ($checkin, $checkout) {
                $checkinDate = date('Y-m-d', strtotime($checkin));
                $checkoutDate = date('Y-m-d', strtotime($checkout));
        
                $q->where(function ($qq) use ($checkinDate, $checkoutDate) {
                    $qq->whereRaw('DATE(start_date) >= ?', [$checkinDate])
                        ->whereRaw('DATE(start_date) < ?', [$checkoutDate]);
                })
                ->orWhere(function ($qq) use ($checkinDate, $checkoutDate) {
                    $qq->whereRaw('DATE(end_date) > ?', [$checkinDate])
                        ->whereRaw('DATE(end_date) <= ?', [$checkoutDate]);
                })
                ->orWhere(function ($qq) use ($checkinDate, $checkoutDate) {
                    $qq->whereRaw('DATE(start_date) <= ?', [$checkinDate])
                        ->whereRaw('DATE(end_date) >= ?', [$checkoutDate]);
                });
            })
            ->get();
            

            // dd($checkin);
            $interval = $today->diffInDays($checkin);
            $day = $interval + 1;
            // dd($day);
            $advancepurchase = AdvancePurchasePrice::whereHas('advancepurchase', function ($query) use ($day, $checkin, $checkout) {
                $query->where('is_active', 1)
                    ->where(function ($query) use ($checkin,$checkout) {
                        $query->whereDate('beginsell', '<=', $checkin)
                              ->whereDate('endsell', '>=', $checkin);
                    })
                    ->where(function ($query) use ($day) {
                        $query->where('day', '<=', $day)
                              ->orWhereNull('day');
                    });
            })
            ->where('vendor_id',$vendorIds)
            ->with('room')
            ->with('users')
            ->with('advancepurchase')
            ->get();
           
            // dd($day,$advancepurchase);
        }

        $data = $vendor;
        // return view('landingpage.hotel.detail',compact('data','roomtype','service','vendordetail','datareq','surcharprice','surchargesVendorIds','blackoutVendorIds'));

        return view('landingpage.hotel.detail', compact('data','HotelCalendar','advancepurchase', 'slider', 'Nights', 'roomtype', 'service', 'vendordetail', 'datareq', 'contractprice','HotelRoomBooking'));
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
