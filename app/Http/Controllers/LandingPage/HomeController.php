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
use App\Models\Popup;
use App\Models\AgentMarkupDetail;
use App\Models\AgentMarkupSetup;
use App\Models\HotelRoomBooking;
use App\Models\HotelRoomSurcharge;
use App\Models\AdvancePurchase;
use App\Models\AdvancePurchasePrice;
use App\Models\SurchargeAllRoom;
use App\Models\BlackoutContractRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client; // Anda perlu menginstal Guzzle HTTP client untuk ini
use Illuminate\Support\Facades\DB;


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

        $today = Carbon::now()->toDateString(); // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD

        $popups = Popup::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
        $hotel = Vendor::where('type_vendor', 'hotel')->count();
        $agent = Vendor::where('type_vendor', 'agent')->count();
        $country = Vendor::where('type_vendor', 'hotel')
        ->distinct()
        ->select('country')
        ->get();

        return view('landingpage.index', compact('slider', 'hotel', 'agent','country','popups'));
    }

    public function hotel(Request $request)
    {

        $checkin = Carbon::parse($request->checkin);
        $checkout = Carbon::parse($request->checkout);
        $Nights = $checkout->diffInDays($checkin);
        $today = Carbon::now();
        $country = Vendor::where('type_vendor', 'hotel')
        ->distinct()
        ->select('country')
        ->get();

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

            $query->when($request->search, function ($q, $search) {
                return $q->where(function ($subquery) use ($search) {
                    $subquery->where('country', 'like', '%' . $search . '%')
                        ->orWhere('vendor_name', 'like', '%' . $search . '%')
                        ->orWhere('state', 'like', '%' . $search . '%')
                        ->orWhere('city', 'like', '%' . $search . '%')
                        ->orWhere(function ($subsubquery) use ($search) {
                            $subsubquery->where('type_property', 'like', '%' . $search . '%');
                        });
                });
            });

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
                $query->where('is_active', 1);
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

                $query->when($request->search, function ($q, $search) {
                    return $q->where(function ($subquery) use ($search) {
                        $subquery->where('country', 'like', '%' . $search . '%')
                            ->orWhere('vendor_name', 'like', '%' . $search . '%')
                            ->orWhere('state', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%')
                            ->orWhere(function ($subsubquery) use ($search) {
                                $subsubquery->where('type_property', 'like', '%' . $search . '%');
                            });
                    });
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
                $query->where('is_active', 1);

                $today = Carbon::now(); // Mengambil tanggal hari ini

                        $query->where(function ($q) use ($checkin, $checkout, $today) {
                            $q->where(function ($qq) use ($checkin, $checkout,$today) {
                                $qq->where('booking_begin', '<=', $today)
                                    ->where('booking_end', '>=', $today);
                            })
                            ->where(function ($qq) use ($checkin, $checkout) {
                                    $qq->where('stayperiod_begin', '<', $checkout)
                                    ->where('stayperiod_end', '>=', $checkin);
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
            ->where('is_active', 1)
            ->with('room')
            ->with('users')
            ->with('advancepurchase')
            ->get();

            $HotelCalendar = HotelRoomSurcharge::where(function ($q) use ($checkin, $checkout) {
                $checkinDate = date('Y-m-d', strtotime($checkin));
                $checkoutDate = date('Y-m-d', strtotime($checkout));

                $q->where(function ($qq) use ($checkinDate, $checkoutDate) {
                    $qq->whereRaw('DATE(start_date) >= ?', [$checkinDate])
                        ->whereRaw('DATE(start_date) <= ?', [$checkoutDate]);
                })
                ->orWhere(function ($qq) use ($checkinDate, $checkoutDate) {
                    $qq->whereRaw('DATE(end_date) >= ?', [$checkinDate])
                        ->whereRaw('DATE(end_date) <= ?', [$checkoutDate]);
                })
                ->orWhere(function ($qq) use ($checkinDate, $checkoutDate) {
                    $qq->whereRaw('DATE(start_date) <= ?', [$checkinDate])
                        ->whereRaw('DATE(end_date) >= ?', [date('Y-m-d', strtotime($checkoutDate . ' +1 day'))]);
                });
            })
            ->get();

            $surchargeAllRoom = SurchargeAllRoom::where('start_date', '>=', $checkin)
            ->where('end_date', '<=', Carbon::parse($checkout)->subDay())
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
            })
            ->orderBy('recomend', 'asc');
        }

        // Eksekusi query dan terapkan paginasi
        $data = $data->simplePaginate(6);

        // Buat array data request
        $requestdata = [
            'country' => $request->country,
            'search' => $request->search,
            'state' => $request->state,
            'person' => $request->person,
            'checkin' => $request->checkin,
            'checkout' => $request->checkout,
            'sort' => $request->sort,
            'properties' =>$selectedProperties
        ];


        // Terapkan append ke objek paginasi
        $data->appends($requestdata);


        // Tanggal setahun ke depan dari sekarang
        $oneYearLater = $today->copy()->addYear();

        // Query untuk mendapatkan data dari tabel HotelRoomSurcharge
        $HotelCalendartool = HotelRoomSurcharge::whereBetween('start_date', [$today, $oneYearLater])
            ->where('end_date', '=', DB::raw('start_date')) // Menambahkan kondisi bahwa enddate sama dengan startdate
            ->get();



        // return view('landingpage.hotel.index',compact('data','requestdata','blackoutVendorIds','surchargesDetail','surcharprice'));
        $acyive = auth()->user()->is_active;
        $agentaffiliate = Vendor::where('user_id',auth()->user()->id)->first();

        $affiliate = $agentaffiliate->affiliate;
        if($acyive == 1){
          return view('landingpage.hotel.index', compact('data',
          'HotelCalendar',
          'HotelCalendartool',
           'requestdata',
           'contractprice',
           'advancepurchase',
           'country',
           'surchargeAllRoom',
           'affiliate',
           'Nights'));
        }else{
            return view('landingpage.pagenotfound.isactiveaccount');
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function hoteldetail(Request $request, $id)
    {

        // dd(auth()->check());
        if(auth()->check() == false){
            return view('auth.login');
        }else{
            $iduser = auth()->user()->id;
            $user = User::where('id', $iduser)->first();
            $agent_country = Vendor::where('user_id', $iduser)->first();
            if($request->country == null){
                $agentCountry = $agent_country->country;
            }else{
                $agentCountry = $request->country;
            }
            
            

            $category = $request->input('data.category');
            $datareq = $request->all();
            // dd($datareq['checkin']);

            $inputCheckin1 = $datareq['checkin'];
            $inputCheckout1 = $datareq['checkout'];
            $checkin2 = Carbon::createFromFormat('Y-m-d', $inputCheckin1);
            $checkout2 = Carbon::createFromFormat('Y-m-d', $inputCheckout1);
            $Nights = $checkout2->diffInDays($checkin2);

            $contract_hotel = ContractRate::where('id', $id)->where('is_active', 1)->first();

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

                        $query->where('is_active', 1);
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

                // dd($agentCountry);
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
                        $query->where('is_active', 1);

                        $today = Carbon::now(); // Mengambil tanggal hari ini

                        $query->where(function ($q) use ($checkin, $checkout, $today) {
                            $q->where(function ($qq) use ($checkin, $checkout,$today) {
                                $qq->where('booking_begin', '<=', $today)
                                    ->where('booking_end', '>=', $today);
                            })
                            ->where(function ($qq) use ($checkin, $checkout) {
                                    $qq->where('stayperiod_begin', '<', $checkout)
                                    ->where('stayperiod_end', '>=', $checkin);
                            });
                        });
                        // $query->where(function ($q) use ($checkin, $checkout) {
                        //     $q->where(function ($qq) use ($checkin, $checkout) {
                        //         $qq->where('booking_begin', '<=', $checkin)
                        //             ->where('booking_end', '>=', $checkout);
                        //     })
                        //     ->where(function ($qq) use ($checkin, $checkout) {
                        //             $qq->where('stayperiod_begin', '<=', $checkin)
                        //             ->where('stayperiod_end', '>=', $checkout);
                        //     });
                        // });
                    })
                    ->orderBy('recom_price', 'asc')
                    ->get();


                // $HotelRoomBooking = HotelRoomBooking::where('vendor_id', $vendorIds)
                //     ->whereHas('booking', function ($query) {
                //         $query->where('booking_status', 'paid');
                //     })
                //     ->where(function ($q) use ($checkin, $checkout) {
                //         $q->where(function ($qq) use ($checkin, $checkout) {
                //             $qq->where('checkin_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
                //                 ->where('checkout_date', '>=', $checkin);
                //         });
                //     })
                //     ->orWhere(function ($q) use ($checkin, $checkout) {
                //         $q->where('checkin_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
                //             ->where('checkout_date', '>=', $checkout)
                //             ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
                //                 $query->select('vendor_id')
                //                     ->from('hotel_room_bookings')
                //                     ->where('checkin_date', '<=', Carbon::createFromFormat('Y-m-d', $checkout)->subDay())
                //                     ->where('checkout_date', '>=', $checkin);
                //             });
                //     })
                //     ->get();
                $HotelRoomBooking = HotelRoomBooking::where('vendor_id', $vendorIds)
                    ->whereHas('booking', function ($query) {
                        $query->where('booking_status', 'paid');
                    })
                    ->where(function ($q) use ($checkin, $checkout) {
                        $q->where(function ($qq) use ($checkin, $checkout) {
                            $qq->where('checkin_date', '<', $checkout)
                                ->where('checkout_date', '>', $checkin);
                        });
                    })
                    ->orWhere(function ($q) use ($checkin, $checkout) {
                        $q->where('checkin_date', '<', $checkout)
                            ->where('checkout_date', '>', $checkout)
                            ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
                                $query->select('vendor_id')
                                    ->from('hotel_room_bookings')
                                    ->where('checkin_date', '<', $checkout)
                                    ->where('checkout_date', '>', $checkin);
                            });
                    })
                    ->get();

                $HotelCalendar = HotelRoomSurcharge::where('vendor_id', $vendorIds)
                ->where(function ($q) use ($checkin, $checkout) {
                    $checkinDate = date('Y-m-d', strtotime($checkin));
                    $checkoutDate = date('Y-m-d', strtotime($checkout));

                    $q->where(function ($qq) use ($checkinDate, $checkoutDate) {
                        $qq->whereRaw('DATE(start_date) >= ?', [$checkinDate])
                            ->whereRaw('DATE(start_date) <= ?', [$checkoutDate]);
                    })
                    ->orWhere(function ($qq) use ($checkinDate, $checkoutDate) {
                        $qq->whereRaw('DATE(end_date) >= ?', [$checkinDate])
                            ->whereRaw('DATE(end_date) <= ?', [$checkoutDate]);
                    })
                    ->orWhere(function ($qq) use ($checkinDate, $checkoutDate) {
                        $qq->whereRaw('DATE(start_date) <= ?', [$checkinDate])
                            ->whereRaw('DATE(end_date) >= ?', [date('Y-m-d', strtotime($checkoutDate . ' +1 day'))]);
                    });
                })
                ->get();

                $surchargeAllRoom = SurchargeAllRoom::where('start_date', '>=', $checkin)
                ->where('end_date', '<=', Carbon::parse($checkout)->subDay())
                ->get();

                $blackoutdate = BlackoutContractRate::where('start_date', '>=', $checkin)
                ->where('end_date', '<=', Carbon::parse($checkout)->subDay())
                ->get();

                // dd($blackoutdate);
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
                ->where('is_active', 1)
                ->with('room')
                ->with('users')
                ->with('advancepurchase')
                ->get();

                // dd($day,$advancepurchase);
            }

            $data = $vendor;
            // dd($contractprice);
            // return view('landingpage.hotel.detail',compact('data','roomtype','service','vendordetail','datareq','surcharprice','surchargesVendorIds','blackoutVendorIds'));
            $agentaffiliate = Vendor::where('user_id',auth()->user()->id)->first();

            $affiliate = $agentaffiliate->affiliate;
            return view('landingpage.hotel.detail',
            compact(
                'data',
                'HotelCalendar',
                'advancepurchase',
                'slider',
                'Nights',
                'roomtype',
                'service',
                'vendordetail',
                'datareq',
                'contractprice',
                'HotelRoomBooking',
                'surchargeAllRoom',
                'affiliate',
                'blackoutdate',
                'user',
                'agentCountry'
            ));
        }
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


    public function doku(Request $request)
    {
        $now = \Carbon\Carbon::now();
        $requestDate = $now->format('Y-m-d\TH:i:s\Z');
        $clientId = env('DOKU_MERCHANT_CODE');
        $requestId = env('DOKU_SHARED_KEY');
        $requestDate = $requestDate;
        $targetPath = "http://127.0.0.1:8006"; // Untuk permintaan dari pedagang ke Jokul, gunakan path Jokul di sini. Untuk Notifikasi HTTP, gunakan path pedagang di sini.
        $secretKey = env('DOKU_SHARED_KEY');
        $requestBody = [
            'order' => [
                'amount' => 15000,
                'invoice_number' => 'INV-20210124-0001',
            ],
            'virtual_account_info' => [
                'expired_time' => 60,
                'reusable_status' => false,
                'info1' => 'Merchant Demo Store',
            ],
            'customer' => [
                'name' => 'Taufik Ismail',
                'email' => 'taufik@example.com',
            ],
        ];

       // Generate Digest
        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

        // Prepare Signature Component
        $componentSignature = "Client-Id:" . $clientId . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $requestDate . "\n" .
            "Request-Target:" . $targetPath . "\n" .
            "Digest:" . $digestValue;

        // Calculate HMAC-SHA256 base64 from all the components above
        $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));
        // $signature = base64_encode(hash_hmac('sha256', json_encode($requestData), $sharedKey, true));
        // Prepare Signature Header
        $headerSignature = [
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestDate,
            'Signature' => "HMACSHA256=" . $signature,
        ];

        // Create the HTTP client
        $httpClient = new Client();

        // Endpoint untuk pembayaran Jokul
        $paymentEndpoint = "https://api-sandbox.doku.com/checkout/v1/payment"; // Gantilah dengan URL yang sesuai dari Jokul

        // Lakukan permintaan pembayaran ke Jokul
        $response = $httpClient->post($paymentEndpoint, [
            'headers' => $headerSignature,
            'json' => $requestBody,
        ]);

        dd($response);
        // Handle respons dari Jokul
        $responseBody = $response->getBody()->getContents();

        // Lakukan apa yang perlu dilakukan dengan respons pembayaran, seperti menampilkan halaman konfirmasi atau menyimpan data pembayaran.

        return response()->json(['paymentResponse' => $responseBody]);
        // Lakukan apa yang perlu dilakukan dengan $headerSignature, misalnya, kirim permintaan HTTP ke layanan Jokul dengan header ini.

        // return response()->json(['headerSignature' => $headerSignature]);
    }

    public function callbackdoku()
    {
        $data = file_get_contents('php://input');
        $response = json_decode($data);

        if ($response->success) {
            dd($response);
        } else {
            dd($response);
            // Proses pembayaran gagal
        }
    }

}
