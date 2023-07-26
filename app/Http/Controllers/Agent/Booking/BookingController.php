<?php

namespace App\Http\Controllers\Agent\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomHotel;
use App\Models\HotelRoomBooking;
use App\Models\PaymentGetwayTransaction;
use App\Models\Vendor;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
    
            // Ambil data check-in dan check-out dari request
            $checkin = Carbon::parse($request->checkin);
            $checkout = Carbon::parse($request->checkout);
    
            // Hitung selisih tanggal check-in dan check-out untuk mendapatkan total malam
            $totalNights = $checkout->diffInDays($checkin);

            $priceString = str_replace(',', '', $request->totalprice);
            // Konversi string harga menjadi angka (integer)
            $price = (int) $priceString;
    
            $data =  new Booking();
            $data->user_id = $userid;
            $data->booking_code = '#BO_'. time().$this->generateRandomString(5).uniqid();
            $data->vendor_id = $request->vendorid;
            $data->booking_date = date("Y-m-d");
            $data->checkin_date = $request->checkin;
            $data->checkout_date = $request->checkout;
            $data->total_room = $request->totalroom;
            $data->night = $totalNights;
            $data->price = $totalNights * $price;
            $data->total_guests = $request->person;
            $data->booking_status = '-';
            $data->save();
    
            $roomData = json_decode($request->room, true);
            foreach($roomData as $item){
                $string = str_replace(',', '', $item['price']);
                // Konversi string harga menjadi angka (integer)
                $priceint = (int) $string;
                
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
                $hotelbook->save();

            }
    
            return response()->json([
                $data->id
            ]);

            // return response()->json(['success' => 'Data saved!']);
            // return redirect()->route('booking.agent.detail',$data->id);
        }
    }

    public function detail($id)
    {
        $data = Booking::where('id',$id)->with('users')->with('vendor')->first();

        $hotelbooking = HotelRoomBooking::where('booking_id',$id)->get();

        return view('landingpage.hotel.bookingpage',compact('data','hotelbooking'));
    }

    public function bookingstore(Request $request, string $id)
    {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required',
                'lastname' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator->errors())
                    ->withInput($request->all());
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

                if($request->paymentmethod == 2){
                    $booking = Booking::find($id);

                    $data['url'] = 'https://sandbox.ipaymu.com/api/v2/payment';
                    $totalbooking = $booking->price;

                    // dd($total);
                    $jml = [
                        'total'=>$totalbooking,
                        'bookingid'=>$booking->id
                    ];
                    $data['body'] = [
                        'name' => array(auth()->user()->name), //array($request->name),
                        'email' => array(auth()->user()->email),//array($request->email),
                        'product' =>array('subscribe'), //array($pacakage),
                        'price' =>array($totalbooking),
                        'qty' => array(1),//array($request->qty),
                        'returnUrl' => route('payment.success',$jml),
                        'notifyUrl' => route('payment.notify'),
                        'comments' => 'Booking Agent',
                        'referenceId' => '1234',
                        'vistreason' => 'chest hurting bad'
                    ];
                    $data['method'] = 'POST';

                    $result = $this->callApiIpaymuBtb($data);
                    // dd($result);
                    if($result['status'] == 200){
                        // DB::beginTransaction();
                        try{
                            $response = json_decode($result['res']);
                            // dd($response);
                            $data['title']  = 'Booking Agent';
                            $data['trxData'] = $response->Data;
                            $data['url'] = $response->Data->Url;
                            // dd($data);
                            $trans = new PaymentGetwayTransaction;
                            $trans->total_transaction = $totalbooking;
                            $trans->code = $response->Data->SessionID;
                            $trans->url_payment = $response->Data->Url;
                            $trans->user_id = auth()->user()->id;
                            $trans->booking_id = $booking->id;
                            $trans->status = $result['status'];
                            $trans->save();

                            return redirect()->to($response->Data->Url);

                        } catch (Exception $e){
                            DB::rollBack();
                            return back()->with('payment error');
                        }
                }
            }
            return redirect()
            ->route('agent.booking.history')
            ->with('success', 'Data saved!');
        }
    }
    
    public function callApiIpaymuBtb($data)
    {
        $body = json_encode($data['body'],JSON_UNESCAPED_SLASHES);

        $requestBody  = strtolower(hash('sha256', $body));
        
        $secret       = 'SANDBOX30C3DCD3-EA90-4DB6-B98A-2F17B6AF6FDA';
        $va           = '0000002413132123';
        $stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');

        $headers = [
            'Content-Type'  =>'application/json',
            'va'            => $va,
            'signature'     => $signature,
            'timestamp'     => $timestamp,
            // 'allow'=>'Access-Control-Allow-Origin: http://127.0.0.1:8000'
        ];

        \Log::info('HIT-IPAYMU-REQUEST'.$va, $data);

        $filename    = storage_path() . '/log/hit-ipaymu/callapi/' . date('Y-m-d') . '.log';
        $directory   = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $clog       = "TIME: ".date('Y-m-d H:i:s')." \n";
        $clog      .= 'IP: ' . request()->ip() ?? 'CRON' . "\n";
        $clog      .= "HEADER :". " \n";
        $clog      .= json_encode($headers) . " \n";
        $clog      .= "REQUEST :". " \n";
        $clog      .= json_encode($data) . " \n";
        $h           = file_put_contents($filename, $clog, FILE_APPEND);

        $client = new Client([
            'headers' => $headers
        ]);

        try {
            $response = $client->request($data['method'], $data['url'],[
                'body' => $body
            ]);

            $data['res'] = $response->getBody()->getContents();
            $data['status'] = 200;

            \Log::info('HIT-IPAYMU-RESPONSE'.$va, $data);

            $clog      .= "STATUS: ". $data['status'] . "\n";
            $clog      .= "RESPONSE: ". json_encode($data['res']) . "\n";
            $clog      .= "-----------------------------------------\n\n";
            $h           = file_put_contents($filename, $clog, FILE_APPEND);

            return $data;

        } catch (ClientException $e) {
            $data['req'] = $e->getRequest();
            $data['resp'] = $e->getResponse();
            $data['msg'] = $e->getMessage();
            $data['status'] = 400;

            \Log::error('HIT-IPAYMU-ERROR'.$va, $data);

            $clog      .= "STATUS: ". $data['status'] . "\n";
            $clog      .= "RESPONSE: ". json_encode($data['resp']) . "\n";
            $clog      .= "ERROR: ". json_encode($data['msg']) . "\n";
            $clog      .= "-----------------------------------------\n\n";
            $h           = file_put_contents($filename, $clog, FILE_APPEND);

            return $data;
        }
    }
    public function paymentsuccess(Request $request){
        // dd($request->via);
        $id = auth()->user()->id;
        if($request->status == 'berhasil'){

            $book = Booking::find($request->bookingid);
            $book->booking_status = 'paid';
            $book->save();
        
            $hotelbook = HotelRoomBooking::where('booking_id',$request->bookingid)->get();

            foreach($hotelbook as $item){
                $room = RoomHotel::find($item->room_id);
                $room->room_allow =  $room->room_allow - $item->total_room;
                $room->save();
            }

            $trans = PaymentGetwayTransaction::where('booking_id',$request->bookingid)->first();
            $trans->payment_method = $request->via;
            $trans->trx_id = $request->trx_id;
            $trans->save();

            $data = [
                'booking' => $book, // $book merupakan instance dari model Booking yang sudah Anda dapatkan
            ];

            // Mail::to($book->vendor->email)->send(new BookingConfirmation($data));
            // Mail::to(auth()->user()->email)->send(new BookingConfirmation($data));

            return redirect()
            ->route('agent.booking.history')
            ->with('success', 'Data saved!');
        }else{
            return abort(404);
        }

        // return view('ipaymu.success');
    }
    public function notify(Request $request){
        
        $id = auth()->user()->id;
        if($request->status == 'berhasil'){

            $book = Booking::find($request->bookingid);
            $book->booking_status = 'paid';
            $book->save();

            $trans = PaymentGetwayTransaction::where('booking_id',$request->bookingid)->first();
            $trans->payment_method = $request->via;
            $trans->trx_id = $request->trx_id;
            $trans->save();

            return redirect()
            ->route('agent.booking.history')
            ->with('success', 'Data saved!');
        }else{
            return abort(404);
        }

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
