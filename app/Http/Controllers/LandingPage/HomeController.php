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
                $slider = Slider::where('user_id', 1)->get();
                // $slider = Slider::all();
            }
        } else {
            $slider = Slider::where('user_id', 1)->get();
            // $slider = Slider::all();
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
            ->with('contractrate.vendors')
            ->with('room');

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
        // Lakukan query ke database dan hasilkan builder query
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

        return view('landingpage.hotel.index', compact('data', 'requestdata'));
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
        // dd($contract_hotel);

        // if ($category) {
        //     $vendor = ContractPrice::whereHas('room', function ($query) use ($category) {
        //         $query->where('id', $category);
        //     })
        //         ->where('contract_id', $id)
        //         ->with('contractrate')
        //         ->with('contractrate.vendors')
        //         ->with('room')
        //         ->get();
        // } else {
            // $vendor = ContractPrice::where('contract_id', $id)
            //     ->with('contractrate')
            //     ->with('contractrate.vendors')
            //     ->with('room')
            //     ->get();
            $vendor = ContractPrice::where('contract_id', $id)
                ->with('contractrate')
                ->with('contractrate.vendors')
                ->with('room')
                ->whereHas('contractrate', function ($query) use ($agentCountry,$Nights) {
                    $query->where('distribute', 'LIKE', '%' . $agentCountry . '%')
                        ->orWhere('distribute', 'LIKE', '%all%');

                    $query->where('min_stay', $Nights)
                    ->orWhere('min_stay', 1);
                })
                ->orderBy('recom_price', 'asc')
                ->get();
        // }

    //    dd($vendor);

        $contractprice = ContractPrice::where('user_id', $vendor[0]->user_id)
            ->with('contractrate')
            ->with('contractrate.vendors')
            ->with('room')
            ->whereHas('contractrate', function ($query) use ($agentCountry) {
                $query->where('rolerate', '=', 2);
                $query->where('distribute', 'LIKE', '%' . $agentCountry . '%');
            })
            ->orderBy('recom_price', 'asc')
            ->get();

        $slider = Slider::where('user_id', $vendor[0]->user_id)->get();

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
            // Ambil semua pemesanan yang sesuai dengan vendor ID
            // $bookings = HotelRoomBooking::where('vendor_id', $vendorIds)->get();



            // // Loop melalui setiap pemesanan
            // foreach ($bookings as $booking) {
            //     $checkinDate = $booking->checkin_date;
            //     $checkoutDate = $booking->checkout_date;
            //     $totalRoomBooked = $booking->total_room;

            //     // Temukan kamar hotel yang sesuai berdasarkan 'room_id' dari pemesanan
            //     $roomHotel = RoomHotel::find($booking->room_id);

            //     // Periksa ketersediaan kamar untuk setiap hari dari checkin hingga checkout
            //     $currentDate = $checkinDate;
            //     while ($currentDate <= $checkoutDate) {
            //         $roomAllowment = $roomHotel->room_allowment;

            //         // Periksa apakah kamar tersedia pada tanggal ini
            //         if ($roomAllowment >= $totalRoomBooked) {
            //             // Jika kamar tersedia, kurangi jumlah kamar yang dipesan dari ketersediaan
            //             $roomAllowment -= $totalRoomBooked;
            //             $roomHotel->room_allowment = $roomAllowment;
            //             $roomHotel->save();

            //         } else {
            //             // Jika kamar tidak tersedia, Anda dapat menangani sesuai kebutuhan Anda, misalnya, menolak pemesanan atau memberikan pesan kesalahan
            //             // Di sini, kita hanya mencetak pesan kesalahan
            //             // dd($roomHotel);
            //         //    dd("Kamar tidak tersedia pada tanggal " . $currentDate . "\n");
            //         }

            //         // Pindah ke tanggal berikutnya
            //         $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
            //     }
            // }

            $HotelRoomBooking = HotelRoomBooking::where('vendor_id', $vendorIds)
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
                $q->where(function ($qq) use ($checkin, $checkout) {
                    $qq->where('start_date', '>=', $checkin)
                        ->where('start_date', '<', $checkout);
                });
            })
            ->orWhere(function ($q) use ($checkin, $checkout) {
                $q->where('end_date', '>', $checkin)
                    ->where('end_date', '<=', $checkout);
            })
            ->get();

            // $HotelCalendar = HotelRoomSurcharge::where('vendor_id', $vendorIds)
            //     ->where(function ($q) use ($checkin, $checkout) {
            //         $q->where(function ($qq) use ($checkin, $checkout) {
            //             $qq->whereRaw("DATE(start_date) <= ?", [$checkout])
            //                 ->whereRaw("DATE(end_date) >= ?", [$checkin]);
            //         });
            //     })
            //     ->orWhere(function ($q) use ($checkin, $checkout) {
            //         $q->whereRaw("DATE(start_date) <= ?", [$checkout])
            //             ->whereRaw("DATE(end_date) >= ?", [$checkin])
            //             ->whereNotIn('vendor_id', function ($query) use ($checkin, $checkout) {
            //                 $query->select('vendor_id')
            //                     ->from('hotel_room_surcharges')
            //                     ->whereRaw("DATE(start_date) <= ?", [$checkout])
            //                     ->whereRaw("DATE(end_date) >= ?", [$checkin]);
            //             });
            //     })
            //     ->where(function ($q) use ($checkin, $checkout) {
            //         $q->whereRaw("DATE(start_date) >= ?", [$checkin])
            //             ->whereRaw("DATE(end_date) <= ?", [$checkout]);
            //     })
            //     ->get();
            // dd($HotelRoomBooking);
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

        return view('landingpage.hotel.detail', compact('data','HotelCalendar', 'slider', 'Nights', 'roomtype', 'service', 'vendordetail', 'datareq', 'contractprice','HotelRoomBooking'));
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
