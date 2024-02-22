<?php

namespace App\Http\Controllers\Api\TravelAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomHotel;
use App\Models\HotelRoomBooking;
use App\Models\PaymentGetwayTransaction;
use App\Models\ContractPrice;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Setting;
use App\Models\OrderTransport;
use Carbon\Carbon;

use App\Models\AgentTransport;
use App\Models\SurchargeAllRoom;
use Illuminate\Support\Str;
use App\Models\ContractRate;

use App\Models\PackageTransport;
use App\Models\TransportDestination;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use App\Mail\PaymentNotif;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $iduser = auth()->user()->id;
        $user = User::where('id',$iduser)->with('vendors')->first();
        $booking = Booking::with('users','vendor')->whereNotIn('booking_status', ['-', ''])->where('user_id',$user->vendors->user_id)->orderBy('created_at', 'desc')->get();
        $transport = OrderTransport::where('user_id',$iduser)->get();

        return response()->json([
            'data' => $booking,
            'agent' => $user,
            'transport' => $transport,
        ],200);

    }
    public function detailbookinginvoice(string $id)
    {
        $iduser = auth()->user()->id;
        $agent = User::where('id',$iduser)->with('vendors')->first();
        $data = Booking::where('id',$id)->with('vendor')->with('users')->first();
        //dd($room);
        $cont_id = HotelRoomBooking::where('booking_id',$data->id)->first();
        $conttract = ContractRate::where('id',$cont_id->contract_id)->first();
        $setting = Setting::first();
        $hotelroombooking = HotelRoomBooking::where('booking_id',$data->id)->with('room')->with('contractprice')->with('contractrate')->with('vendors')->get();
        
        foreach($hotelroombooking as $item){
            // $room = RoomHotel::where('id',$item->room_id)->first();
            if($item->room_name == null){
                $item->room_name = $item->room->ratedesc;
                $item->contract_name = $item->contractrate->codedesc;
                $item->benefit_policy = $item->contractrate->benefit_policy;
                $item->other_policy = $item->contractrate->other_policy;
                $item->cencellation_policy = $item->contractrate->cencellation_policy;
                $item->deposit_policy = $item->contractrate->deposit_policy;
                $item->save();
            }
        }

        $transport = OrderTransport::where('booking_id',$id)->with('agenttransport')->first();
        
        return response()->json([
            'data' => $data,
            'agent' => $agent,
            'setting' => $setting,
            'contract' => $conttract,
            'roombooking' =>$hotelroombooking,
            'transport' => $transport
        ],200);
    }

    public function createbooking(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'checkin' => 'required',
            'checkout' => 'required',
            'person' => 'required',
            // Add other validation rules if needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $userid = auth()->user()->id;

            $roomData = json_decode($request->room, true);
            // dd($roomData);
            // Ambil data check-in dan check-out dari request
            $checkin = Carbon::parse($request->checkin);
            $checkout = Carbon::parse($request->checkout);

            // Hitung selisih tanggal check-in dan check-out untuk mendapatkan total malam
            $totalNights = $checkout->diffInDays($checkin);

            $priceString = str_replace(',', '', $request->totalprice);
            // Konversi string harga menjadi angka (integer)
            $price = (int) $priceString;

            $pricenomarkupString = str_replace(',','', $request->totalpricenomarkup);

            $pricenomarkup = (int) $pricenomarkupString;

            $surchargeAllRoom = SurchargeAllRoom::where('start_date', '>=', $checkin)
            ->where('end_date', '<=', Carbon::parse($checkout)->subDay())
            ->where('vendor_id',$request->vendorid)
            ->get();

            $totalsurchargex = 0;
            $surchargepricetotalx = 0;
            foreach ($surchargeAllRoom as $surchargeAllRoomitem) {
                    $totalsurchargex += $surchargeAllRoomitem->surcharge_price;
                    // $totalDataCountx++;
            }
            $surcharge = $totalsurchargex/$totalNights;

            $data =  new Booking();
            $data->user_id = $userid;
            $data->booking_code = '#BO_'. $this->generateRandomString(10);
            $data->vendor_id = $request->vendorid;
            $data->booking_date = date("Y-m-d");
            $data->checkin_date = $request->checkin;
            $data->checkout_date = $request->checkout;
            $data->total_room = $request->totalroom;
            $data->night = $totalNights;
            $data->price = $totalNights * $price;
            $data->pricenomarkup = $totalNights * ($pricenomarkup + $surcharge);
            $data->total_guests = $request->person;
            $data->booking_status = '-';
            $data->save();

            foreach($roomData as $item){
                $string = str_replace(',', '', $item['price']);
                // Konversi string harga menjadi angka (integer)
                $priceint = (int) $string;

                $pString = str_replace(',','',$item['pricenomarkup']);

                $pricenomarkupint = (int) $pString;

                $contractprice = ContractPrice::where('id',$item['contpriceid'])->with('contractrate')->first();

                $roomhotel = RoomHotel::where('id',$item['roomId'])->first();

                $hotelbook = new HotelRoomBooking();
                $hotelbook->room_id = $item['roomId'];
                $hotelbook->booking_id = $data->id;
                $hotelbook->vendor_id = $request->vendorid;
                $hotelbook->contract_price_id = $item['contpriceid'];
                $hotelbook->contract_id = $item['contractid'];
                $hotelbook->total_room = $item['quantity'];
                $hotelbook->checkin_date = $request->checkin;
                $hotelbook->checkout_date = $request->checkout;
                $hotelbook->price = $priceint;
                $hotelbook->room_name = $roomhotel->ratedesc;

                $hotelbook->contract_name = $contractprice->contractrate->codedesc;
                $hotelbook->benefit_policy = $contractprice->contractrate->benefit_policy;
                $hotelbook->other_policy = $contractprice->contractrate->other_policy;
                $hotelbook->cencellation_policy = $contractprice->contractrate->cencellation_policy;
                $hotelbook->deposit_policy = $contractprice->contractrate->deposit_policy;

                $hotelbook->rate_price = $contractprice->recom_price + $surcharge;
                $hotelbook->total_ammount = ((($contractprice->recom_price + $surcharge) * $totalNights) * $item['quantity']);
                $hotelbook->pricenomarkup = $pricenomarkupint + ($surcharge * $totalNights);
                $hotelbook->save();

            }

            $hotelbook_where_totalamount = HotelRoomBooking::where('booking_id',$data->id)->get();

            $pricenomaruptotal = 0;
            foreach($hotelbook_where_totalamount as $hbwt){
                $pricenomaruptotal += $hbwt->total_ammount;
            }

            $booking_update_pricenomarkup = Booking::find($data->id);
            $booking_update_pricenomarkup->pricenomarkup = $pricenomaruptotal;
            $booking_update_pricenomarkup->save();

            return response()->json([
                'booking_id'=>$data->id
            ],200);

            // return response()->json(['success' => 'Data saved!']);
            // return redirect()->route('booking.agent.detail',$data->id);
        }
    }

    public function detailbooking($id)
    {
        $data = Booking::where('id',$id)->with('users')->with('vendor')->first();

        $transport = PackageTransport::with('transportdestination')->with('agenttransport')->whereHas('agenttransport', function ($query) {
            $query->where('status', 1);
        })->get();
        $destination = TransportDestination::where('state',$data->vendor->state)->get();
        $hotelbooking = HotelRoomBooking::where('booking_id',$id)->get();

        return response()->json([
            'data' => $data,
            'hotelbooking' => $hotelbooking,
            'transport' => $transport,
            'destination'=>$destination
        ], 200);

    }

    public function bookingstore(Request $request, string $id)
    {
        // dd($request->totalPrice);
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
                'lastname' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                $book = Booking::find($id);
                $book->first_name = $request->firstname;
                $book->last_name = $request->lastname;
                $book->email = $request->email;
                $book->phone = $request->phone;
                $book->address_line1 = $request->address1;
                $book->address_line2 = $request->address2;
                $book->zip_code = $request->zipcode;
                $book->city = $request->city;
                $book->country = $request->country;
                $book->state = $request->state;
                $book->special_request = $request->special;
                $book->payment_method = $request->paymentmethod;
                $book->booking_status = 'unpaid';
                $book->save();

                if ($request->idtransport) {
                    $package = PackageTransport::find($request->idtransport);

                    $ordertransport = new OrderTransport;
                    $ordertransport->user_id = $book->user_id;
                    $ordertransport->package_id = $request->idtransport;
                    $ordertransport->booking_id = $id;
                    $ordertransport->transport_id = $package->agenttransport->id;
                    $ordertransport->time_pickup = $request->timepickup;
                    $ordertransport->flight_time = $request->flight;
                    $ordertransport->pickup_date = $request->datepickup;
                    $ordertransport->guest_name = $request->firstname.' '.$request->lastname;
                    $ordertransport->phone_guest = $request->phone;
                    $ordertransport->total_price_nomarkup = $package->price;
                    $ordertransport->total_price = $package->price + $package->agenttransport->markup;
                    $ordertransport->is_see = 0;
                    $ordertransport->destination = $package->transportdestination->destination;
                    $ordertransport->typecar = $package->type_car;
                    $ordertransport->number_police = $package->number_police;
                    $ordertransport->booking_status = 'unpaid';
                    $ordertransport->save();
                }

                $booking = $id;
                $hotel_room_booking = HotelRoomBooking::where('booking_id',$booking)->get();
                $totalprice = 0;
                foreach($hotel_room_booking as $item){
                    $totalprice += $item->price;
                }

                $booking = Booking::find($booking);
                $totalpayment = $booking->night * $totalprice;

                $total_as_saldo = 0;
                if($booking->price == $totalpayment){
                    $total_as_saldo = $booking->price;
                }else{
                    $total_as_saldo = $totalpayment;
                }

                if ($request->idtransport) {
                    $tranportbookings = OrderTransport::where('booking_id',$id)->first();
                    $transportbooking = $tranportbookings->total_price;
                }else{
                    $transportbooking = 0;
                }


                return response()->json([
                    'booking'=>$booking,
                    'total_as_saldo'=>$total_as_saldo,
                    'transportbooking'=>$tranportbookings
                ], 200);
          
        }
    }
    
    public function paymentbookingpage($booking){
        $hotel_room_booking = HotelRoomBooking::where('booking_id',$booking)->get();
        $totalprice = 0;
        foreach($hotel_room_booking as $item){
            $totalprice += $item->price;
        }

        $booking = Booking::find($booking);
        $totalpayment = $booking->night * $totalprice;

        $total_as_saldo = 0;
        if($booking->price == $totalpayment){
            $total_as_saldo = $booking->price;
        }else{
            $total_as_saldo = $totalpayment;
        }

        $tranportbookings = OrderTransport::where('booking_id',$booking->id)->first();
        if($tranportbookings){
            $transportbooking = $tranportbookings->total_price;
        }else{
            $transportbooking = 0;
        }

        return response()->json([
            'booking'=>$booking,
            'total_as_saldo'=>$total_as_saldo,
            'transportbooking'=>$tranportbooking
        ], 200);

    }

    function generateRandomString($length) {
        $characters = '0123456789QWERTYUIOPASDFGHJKLZXCVBNM';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
