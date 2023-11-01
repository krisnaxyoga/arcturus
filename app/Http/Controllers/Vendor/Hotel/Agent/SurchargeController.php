<?php

namespace App\Http\Controllers\Vendor\Hotel\Agent;

use App\Http\Controllers\Controller;
use App\Models\BarPrice;
use App\Models\BarRoom;
use App\Models\HotelRoomSurcharge;
use App\Models\SurchargeAllRoom;
use App\Models\RoomHotel;
use App\Models\Vendor;
use App\Models\ContractPrice;
use App\Models\ContractRate;
use Carbon\CarbonPeriod;
use App\Models\HotelRoomBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SurchargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userid = Auth::id();

        $vendor = Vendor::query()->where('user_id', $userid)->with('users')->first();


        $ContractPrice = ContractPrice::where('contract_prices.user_id', $userid)
            ->with('contractrate')
            ->with('room')
            ->orderBy('recom_price', 'asc')
            ->join('room_hotels', 'contract_prices.room_id', '=', 'room_hotels.id')
            ->get();

        $ContractRate1 = ContractRate::query()->where('user_id', $userid)->where('vendor_id', $vendor->id)->where('rolerate', 1)->first();

        $ContractRate = ContractRate::query()->where('user_id', $userid)->where('vendor_id', $vendor->id)->get();

        // dd($ContractPrice);

        if ($ContractPrice->isEmpty()) {
            $room = RoomHotel::query()->where('user_id', $userid)->get();
        } else {
            $room = ContractPrice::where('contract_prices.user_id', $userid)
                ->where('contract_prices.contract_id', $ContractRate1->id)
                ->with('contractrate')
                ->with('room')
                ->orderBy('recom_price', 'asc')
                ->join('room_hotels', 'contract_prices.room_id', '=', 'room_hotels.id')
                ->get();
        }


        $default_selected_hotel_room = collect($room)->first();
        // dd($room,$default_selected_hotel_room);

        return inertia('Vendor/MenageRoom/Surcharge/Index', [
            'default_selected_hotel_room' => $default_selected_hotel_room,
            'hotel_rooms' => $room,
            'vendor' => $vendor,
            'contractrate' => $ContractRate
        ]);
    }

    public function load_dates(Request $request, $hotel_room_id): JsonResponse
    {
        $from = date('Y-m-d', strtotime($request->start));
        $to = date('Y-m-d', strtotime($request->end));

        $period = CarbonPeriod::create($from, $to);

        $data = [];
        $userid = Auth::id();

        $vendorIds = Vendor::where('user_id', $userid)->first();


        foreach ($period as $date) {
            $hotel_room_surcharge = HotelRoomSurcharge::query()
                ->where('room_hotel_id', $hotel_room_id)
                ->where('start_date', $date)
                ->first();

            $HotelRoomBooking = HotelRoomBooking::where('vendor_id', $vendorIds->id)
                ->where('room_id', $hotel_room_id)
                ->whereHas('booking', function ($query) {
                    $query->where('booking_status', 'paid');
                })
                ->where(function ($query) use ($date) {
                    // Checkin date before or equal to $date AND checkout date after or equal to $date
                    $query->where('checkin_date', '<=', $date)
                        ->where('checkout_date', '>=', $date);
                })
                ->orWhere(function ($query) use ($date) {
                    // Checkin date before or equal to $date AND checkout date after or equal to $date
                    $query->where('checkin_date', '<=', $date)
                        ->where('checkout_date', '>=', $date);

                    // Vendor ID is NOT IN the list of vendor IDs that have a booking on the same date
                    $query->whereNotIn('vendor_id', function ($query) use ($date) {
                        $query->select('vendor_id')
                            ->from('hotel_room_bookings')
                            ->where('checkin_date', '<=', $date)
                            ->where('checkout_date', '>=', $date);
                    });
                })
                ->first();


            // $ContractPrice = ContractPrice::where('room_id', $hotel_room_id)
            //     ->where('user_id', $userid)
            //     ->first();
            $ContractPrice = ContractPrice::where('room_id', $hotel_room_id)
                // ->where('contract_prices.contract_id',$contract_id)
                ->where('contract_prices.user_id', $userid)
                ->with('contractrate')
                ->with('room')
                ->whereHas('contractrate', function ($query) {
                    $query->where('rolerate', 1); // Ganti 'your_column_name' dengan nama kolom yang sesuai
                })
                ->orderBy('recom_price', 'asc')
                ->join('room_hotels', 'contract_prices.room_id', '=', 'room_hotels.id')
                ->first();

            if ($HotelRoomBooking) {
                $roomallow = $ContractPrice->room->room_allow - $HotelRoomBooking->total_room;
            } else {
                $roomallow = $ContractPrice->room->room_allow;
            }

            if ($hotel_room_surcharge) {
                // $price = $hotel_room_surcharge->price; // Ambil harga sebagai decimal
                // $formattedPrice = number_format($price, 0, ',', '.'); // Format harga tanpa desimal nol

                $roomallow = $hotel_room_surcharge->room_allow;

                if ($hotel_room_surcharge->active == 1 && $roomallow != 0) {
                    if ($hotel_room_surcharge->no_checkout == 1 && $hotel_room_surcharge->no_checkin == 0) {
                        $color = '#941b0c';
                    } elseif ($hotel_room_surcharge->no_checkout == 0 && $hotel_room_surcharge->no_checkin == 1) {
                        $color = '#bc3908';
                    } elseif ($hotel_room_surcharge->no_checkout == 1 && $hotel_room_surcharge->no_checkin == 1) {
                        $color = '#621708';
                    } else {
                        $color = '#2a9134';
                    }
                } else {
                    $color = '#e63946';
                }
                $data[] = [
                    'title' => 'Rp ' . number_format($hotel_room_surcharge->recom_price, 0, ',', '.').' x '.$roomallow,
                    'start' => date('Y-m-d', strtotime($hotel_room_surcharge->start_date)),
                    'end' => date('Y-m-d', strtotime($hotel_room_surcharge->end_date) + 86400),
                    'price' => $hotel_room_surcharge->recom_price,
                    'color' => $color,
                    'allow' => $roomallow,
                    'night'=> $hotel_room_surcharge->night,
                    'nocheckin' => $hotel_room_surcharge->no_checkin,
                    'nocheckout' => $hotel_room_surcharge->no_checkout
                ];
            } else {
                $data[] = [
                    'title' => 'Rp ' . number_format($ContractPrice->recom_price, 0, ',', '.').' x '.$roomallow,
                    'date' => date('Y-m-d', strtotime($date)),
                    'price' => $ContractPrice->recom_price,
                    'allow' => $roomallow,
                    'night' => 1,
                    'color' => '#0077b6',
                    'nocheckin' => 0,
                    'nocheckout' => 0
                ];
            }
        }

        return response()->json($data);
    }



    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request): void
    {
        $request->validate([
            'vendor_id' => 'required',
            'room_hotel_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $current_date = $start_date;

        while ($current_date <= $end_date) {
            $hotel_room_surcharge = HotelRoomSurcharge::query()
                ->where('vendor_id', $request->vendor_id)
                ->where('room_hotel_id', $request->room_hotel_id)
                ->where('start_date', $current_date)
                ->first();

            if (! $hotel_room_surcharge) {
                $hotel_room_surcharge = new HotelRoomSurcharge();
            }

            $hotel_room_surcharge->vendor_id = $request->vendor_id;
            $hotel_room_surcharge->room_hotel_id = $request->room_hotel_id;
            $hotel_room_surcharge->start_date = $current_date;
            $hotel_room_surcharge->end_date = $current_date; // End date is the same as start date for daily entries
            $hotel_room_surcharge->recom_price = $request->price;
            $hotel_room_surcharge->no_checkin = $request->nocheckin;
            $hotel_room_surcharge->no_checkout = $request->nocheckout;
            $hotel_room_surcharge->night = $request->night;
            if ($request->room_allow == null || $request->room_allow == 0) {
                $hotel_room_surcharge->room_allow = 0;
                $hotel_room_surcharge->active = 0;
            } else {
                $hotel_room_surcharge->room_allow = $request->room_allow;
                $hotel_room_surcharge->active = 1;
            }

            $hotel_room_surcharge->save();

            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day')); // Move to the next day
        }
    }

    // public function store(Request $request): void
    // {
    //     $request->validate([
    //         'vendor_id' => 'required',
    //         'room_hotel_id' => 'required',
    //         'start_date' => 'required',
    //         'end_date' => 'required',
    //     ]);

    //     $start_date = date('Y-m-d', strtotime($request->start_date));
    //     $end_date = date('Y-m-d', strtotime($request->end_date));

    //     $hotel_room_surcharge = HotelRoomSurcharge::query()->where('vendor_id', $request->vendor_id)
    //         ->where('room_hotel_id', $request->room_hotel_id)
    //         ->where('start_date', $start_date)
    //         ->first();

    //     if (!$hotel_room_surcharge) {
    //         // Cek jika ada entri sebelumnya dengan harga yang sama
    //         $previousSurcharge = HotelRoomSurcharge::where('vendor_id', $request->vendor_id)
    //             ->where('room_hotel_id', $request->room_hotel_id)
    //             ->where('recom_price', $request->price)
    //             ->where('room_allow', $request->room_allow)
    //             ->orderBy('end_date', 'desc')
    //             ->first();

    //         if ($previousSurcharge) {
    //             // Jika ada entri sebelumnya, periksa jika ada kesenjangan atau gap
    //             if (date('Y-m-d', strtotime('+1 day', strtotime($previousSurcharge->end_date))) == $start_date) {
    //                 // Tidak ada kesenjangan, gabungkan atau edit rentang yang ada
    //                 $start_date = $previousSurcharge->start_date;
    //                 $previousSurcharge->end_date = $end_date;
    //                 $previousSurcharge->save();
    //                 return;
    //             }
    //         }

    //         // Jika tidak ada entri sebelumnya atau ada kesenjangan, buat entri baru
    //         $newSurcharge = new HotelRoomSurcharge();
    //         $newSurcharge->vendor_id = $request->vendor_id;
    //         $newSurcharge->room_hotel_id = $request->room_hotel_id;
    //         $newSurcharge->start_date = $start_date;
    //         $newSurcharge->end_date = $end_date;
    //         $newSurcharge->recom_price = $request->price;
    //         // $newSurcharge->active = $request->active;
    //         $newSurcharge->no_checkin = $request->nocheckin;
    //         $newSurcharge->no_checkout = $request->nocheckout;

    //         if ($request->room_allow == null || $request->room_allow == 0) {
    //             $newSurcharge->room_allow = 0;
    //             $newSurcharge->active = 0;
    //         } else {
    //             $newSurcharge->room_allow = $request->room_allow;
    //             $newSurcharge->active = 1;
    //         }

    //         $newSurcharge->save();
    //     } else {
    //         // Mencari semua entri yang memiliki harga yang sama dengan yang baru
    //         $surchargeEntries = HotelRoomSurcharge::query()
    //             ->where('vendor_id', $request->vendor_id)
    //             ->where('room_hotel_id', $request->room_hotel_id)
    //             ->where('recom_price', $request->price)
    //             ->orderBy('start_date')
    //             ->get();
    //         if (!$surchargeEntries->isEmpty()) {
    //             foreach ($surchargeEntries as $entry) {
    //                 if ($entry->id == $hotel_room_surcharge->id) {
    //                     // Skip entri yang sedang diperbarui
    //                     continue;
    //                 }

    //                 if ($start_date <= $entry->end_date && $end_date >= $entry->start_date) {
    //                     // Tanggal yang akan diedit tumpang tindih dengan entri yang ada
    //                     if ($start_date <= $entry->start_date && $end_date > $entry->end_date) {
    //                         // Tanggal yang akan diedit mencakup entri yang ada sepenuhnya, hapus entri yang ada

    //                         $entry->delete();
    //                     } elseif ($start_date <= $entry->start_date && $end_date < $entry->end_date) {
    //                         // Tanggal yang akan diedit dimulai dari entri yang ada,
    //                         // perbarui tanggal awal entri yang ada
    //                         $entry->start_date = $end_date;
    //                         $entry->save();
    //                     } elseif ($start_date > $entry->start_date && $end_date >= $entry->end_date) {
    //                         // Tanggal yang akan diedit mengakhiri entri yang ada,
    //                         // perbarui tanggal akhir entri yang ada
    //                         $entry->end_date = $start_date;
    //                         $entry->save();
    //                     }
    //                 }
    //             }
    //         }

    //         $hotel_room_surcharge->vendor_id = $request->vendor_id;
    //         $hotel_room_surcharge->room_hotel_id = $request->room_hotel_id;
    //         $hotel_room_surcharge->start_date = $start_date;
    //         $hotel_room_surcharge->end_date = $end_date;
    //         $hotel_room_surcharge->recom_price = $request->price;
    //         $hotel_room_surcharge->active = $request->active;
    //         $hotel_room_surcharge->no_checkin = $request->nocheckin;
    //         $hotel_room_surcharge->no_checkout = $request->nocheckout;
    //         if ($request->room_allow == null || $request->room_allow == 0) {
    //             $hotel_room_surcharge->room_allow = 0;
    //             $hotel_room_surcharge->active = 0;
    //         } else {
    //             $hotel_room_surcharge->room_allow = $request->room_allow;
    //             $hotel_room_surcharge->active = 1;
    //         }
    //         $hotel_room_surcharge->save();
    //     }
    // }




    // public function store(Request $request): void
    // {
    //     $request->validate([
    //         'vendor_id' => 'required',
    //         'room_hotel_id' => 'required',
    //         'start_date' => 'required',
    //         'end_date' => 'required',
    //     ]);

    //     $start_date = date('Y-m-d', strtotime($request->start_date));
    //     $end_date = date('Y-m-d', strtotime($request->end_date));

    //     $hotel_room_surcharge = HotelRoomSurcharge::query()->where('vendor_id', $request->vendor_id)
    //         ->where('room_hotel_id', $request->room_hotel_id)
    //         ->where('start_date', $start_date)
    //         ->first();

    //     if (! $hotel_room_surcharge) {
    //         $hotel_room_surcharge = new HotelRoomSurcharge();
    //     }

    //     $hotel_room_surcharge->vendor_id = $request->vendor_id;
    //     $hotel_room_surcharge->room_hotel_id = $request->room_hotel_id;
    //     $hotel_room_surcharge->start_date = $start_date;
    //     $hotel_room_surcharge->end_date = $end_date;
    //     $hotel_room_surcharge->recom_price = $request->price;
    //     $hotel_room_surcharge->active = $request->active;
    //     $hotel_room_surcharge->no_checkin = $request->nocheckin;
    //     $hotel_room_surcharge->no_checkout = $request->nocheckout;
    //     if($request->room_allow == null || $request->room_allow == 0){
    //         $hotel_room_surcharge->room_allow = 0;
    //     }else{
    //         $hotel_room_surcharge->room_allow = $request->room_allow;
    //     }
    //     $hotel_room_surcharge->save();

    // }

    /**
     * Display the specified resource.
     */
    public function surchargeallroom()
    {
        $userid = Auth::id();

        $vendor = Vendor::query()->where('user_id', $userid)->with('users')->first();

        $today = Carbon::now();
        $tomorrow = $today->addDay();

        $surchargeallroom = SurchargeAllRoom::where('user_id', $userid)
        ->select('code', 'stayperiod_start', 'stayperiod_end','surcharge_price')
        ->groupBy('code', 'stayperiod_start', 'stayperiod_end','surcharge_price')
        ->orderBy('stayperiod_start', 'asc')
        ->get();

        return inertia('Vendor/MenageRoom/Surcharge/SurchargeAllRoom',[
            'vendor' => $vendor,
            'surchargeallroom' => $surchargeallroom
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function surchargeallroomstore(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $userid = Auth::id();

        $vendor = Vendor::query()->where('user_id', $userid)->with('users')->first();
        $code = Str::random(8);
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $current_date = $start_date;

        while ($current_date <= $end_date) {
            $hotel_room_surcharge = SurchargeAllRoom::query()
                ->where('vendor_id', $vendor->id)
                ->where('start_date', $current_date)
                ->first();

            if (! $hotel_room_surcharge) {
                $hotel_room_surcharge = new SurchargeAllRoom();
            }

            $hotel_room_surcharge->user_id = $userid;
            $hotel_room_surcharge->vendor_id = $vendor->id;
            $hotel_room_surcharge->stayperiod_start = $request->start_date;
            $hotel_room_surcharge->stayperiod_end = $request->end_date;
            $hotel_room_surcharge->start_date = $current_date;
            $hotel_room_surcharge->end_date = $current_date; // End date is the same as start date for daily entries
            $hotel_room_surcharge->surcharge_price = $request->surchargeprice;
            $hotel_room_surcharge->status = 1;
            $hotel_room_surcharge->code = $code;
    
            $hotel_room_surcharge->save();

            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day')); // Move to the next day
        }

        
        return redirect()->back()->with('success', 'Data Saved!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function surchargeallroomdestroy($code)
    {
        $hotel_room_surcharge = SurchargeAllRoom::query()
            ->where('code', $code)
            ->get();
        foreach ($hotel_room_surcharge as $item){
            $item->delete();
        }
       

        return redirect()->back()->with('success', 'surcharge deleted');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($vendorid,$roomid,$startdate)
    {
        $hotel_room_surcharge = HotelRoomSurcharge::query()->where('vendor_id', $vendorid)
            ->where('room_hotel_id', $roomid)
            ->where('start_date', $startdate)
            ->first();
        if($hotel_room_surcharge){
                $hotel_room_surcharge->delete();
        }
       

        return redirect()->back()->with('success', 'rate deleted');
    }
}
