<?php

namespace App\Http\Controllers\Api\TravelAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Booking;
use App\Models\OrderTransport;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            
            $iduser = auth()->user()->id;
            //$vendor = User::where('id',$iduser)->with('vendors')->first();
            $data = User::where('id',$iduser)->with('vendors')->first();

            $totalbooking = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->sum('price');
            $bookingsuccess = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->count();
            $pendingpayment = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','unpaid')->count();
            $roomhotel1 = Booking::where('user_id',$data->vendors->user_id)->where('booking_status','paid')->get();
            $roomhotel = 0;
            foreach($roomhotel1 as $item){
                $roomhotel += $item->night * $item->total_room;
            }
            $bookingdata = Booking::where('user_id',$data->vendors->user_id)->with('users','vendor')->whereNotIn('booking_status', ['-', ''])->orderBy('created_at', 'desc')->get();
            $acyive = auth()->user()->is_active;
            $transport = OrderTransport::where('user_id',$iduser)->get();
            if($acyive == 1){
                return response()->json([
                    'data' => $data,
                    'booking' => $totalbooking,
                    'success' => $bookingsuccess,
                    'pending' => $pendingpayment,
                    'getbooking' => $bookingdata,
                    'totalroom' => $roomhotel,
                    'transport' => $transport
                ],200);
            }else{
                return response()->json([
                    'message' => 'USER IS NOT ACTIVE'
                ],200);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $exception->getMessage(),
            ], 500);
        }
        

    }
}
