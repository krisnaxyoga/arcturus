<?php

namespace App\Http\Controllers\Api\TravelAgent;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryWallet;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\PaymentGetwayTransaction;
use App\Models\ContractRate;
use App\Models\HotelRoomBooking;
use App\Models\Booking;
use App\Mail\TopUpAdminConfirmation;
use App\Mail\BookingConfirmation;
use App\Mail\BookingConfirmationHotel;
use App\Models\OrderTransport;
use Illuminate\Support\Facades\Mail;

class WalletController extends Controller
{
    public function index()
    {
        try {
            $iduser = auth()->user()->id;
            $agent = User::where('id',$iduser)->with('vendors')->first();
            $history = HistoryWallet::where('user_id',$iduser)->orderBy('created_at', 'desc')->get();
            $setting = Setting::first();
            
            return response()->json([
                'agent' => $agent,
                'history' => $history,
                'setting' => $setting,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }


    public function pay($id)
    {
        try {
            // Mendapatkan detail pemesanan transportasi
            $transportbooking = OrderTransport::where('booking_id', $id)->first();
    
            if ($transportbooking) {
                $transportbooking->booking_status = 'paid';
                $transportbooking->save();
                $totaltranport = $transportbooking->total_price;
            } else {
                $totaltranport = 0;
            }
    
            // Mendapatkan detail pemesanan kamar hotel
            $hotel_room_booking = HotelRoomBooking::where('booking_id', $id)->get();
            $saldo = auth()->user()->saldo;
            $totalprice = 0;
    
            // Menghitung total harga kamar hotel
            foreach ($hotel_room_booking as $item) {
                $totalprice += $item->price;
            }
    
            // Mendapatkan detail pemesanan
            $booking = Booking::find($id);
            $totalpayment = ($booking->night * $totalprice) + $totaltranport;
    
            // Memeriksa apakah saldo mencukupi
            if ($saldo < $totalpayment) {
                $message = 'you must top up';
                return response()->json([
                    'message' => $message,
                ], 400);
            }
    
            // Menghitung saldo akhir setelah pembayaran
            $total_as_saldo = $saldo - $totalpayment;
            $minsaldo = $totalpayment;
    
            // Menyimpan riwayat transaksi pembayaran
            $history = new HistoryWallet;
            $history->saldo_master = $saldo;
            $history->total_saldo = $total_as_saldo;
            $history->saldo_add_minus = $minsaldo;
            $history->user_id = auth()->user()->id;
            $history->vendor_id = auth()->user()->vendor_id;
            $history->type_transaction = 'PAYMENT-SALDO';
            $history->status = 'success';
            $history->save();
    
            // Mengurangi saldo pengguna
            $user = User::where('id', auth()->user()->id)->first();
            $user->saldo = $total_as_saldo;
            $user->save();
    
            // Menandai pemesanan sebagai 'paid'
            $booking->booking_status = 'paid';
            $booking->payment_method = 2;
            $booking->is_see = 0;
            $booking->save();
    
            // Mengirim email konfirmasi pemesanan
            $hotelbook = HotelRoomBooking::where('booking_id', $id)->get();
            $contract_id = HotelRoomBooking::where('booking_id', $id)->first();
            $contract = ContractRate::where('id', $contract_id->contract_id)->first();
            $agent = Vendor::where('user_id', $booking->user_id)->first();
            $vendor = Vendor::where('id', $booking->vendor_id)->first();
            $affiliator = Vendor::where('affiliate', $vendor->affiliate)->where('type_vendor', 'agent')->first();
    
            $data = [
                'booking' => $booking, // $book merupakan instance dari model Booking yang sudah Anda dapatkan
                'contract' => $contract,
                'setting' => Setting::first(),
                'agent' => $agent,
                'hotelbook' => $hotelbook,
                'affiliator' => $affiliator
            ];
    
            if (env('APP_ENV') == 'production') {
                Mail::to($booking->vendor->email_reservation)->send(new BookingConfirmationHotel($data));
                Mail::to($booking->vendor->email)->send(new BookingConfirmationHotel($data));
                Mail::to($booking->users->email)->send(new BookingConfirmation($data));
            }
    
            $message = 'payment success';
            return response()->json([
                'message' => $message,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            
            $validator =  Validator::make($request->all(), [
                'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ], [
                'image.mimes' => 'The image must be in PNG, JPG, or JPEG format.',
                'image.max' => 'The image size cannot exceed 2MB.',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            } else {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('buktiftranfer'), $filename);
    
                    // Lakukan hal lain yang diperlukan, seperti menyimpan nama file dalam database
                }else{
                    $filename= "";
                }
    
                $feature = "/buktiftranfer/".$filename;
    
    
                $userid = auth()->user()->id;
    
                $total_saldo = auth()->user()->saldo + $request->totaltopup;
    
                $history = new HistoryWallet;
                $history->saldo_master = auth()->user()->saldo;
                $history->total_saldo = $total_saldo;
                $history->saldo_add_minus = $request->totaltopup;
                $history->user_id = auth()->user()->id;
                $history->vendor_id = auth()->user()->vendor_id;
                $history->type_transaction = 'TOP-UP-SALDO';
                $history->status = 'proccessing';
                $history->save();
    
                // $user = User::find(auth()->user()->id);
                // $user->saldo = $total_saldo;
                // $user->save();
    
                $trans = new PaymentGetwayTransaction;
                $trans->total_transaction = $request->totaltopup;
                $trans->code = Str::random(20);
                $trans->url_payment = $feature;
                $trans->user_id = auth()->user()->id;
                $trans->booking_id = 0;
                $trans->status = 400;
                $trans->payment_method = 'BANK-TRANSFER-TOP-UP';
                $trans->trx_id = $history->id;
                $trans->is_see = 0;
                $trans->save();
    
                $Setting = Setting::where('id',1)->first();
                if (env('APP_ENV') == 'production') {
                Mail::to('accounting@arcturus.my.id')->send(new TopUpAdminConfirmation($trans));
                }
    
                return response()->json([
                    'message' => 'Top Up Proccess!',
                ], 200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
       
    }
}
