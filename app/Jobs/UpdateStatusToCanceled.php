<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class UpdateStatusToCanceled implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       Booking::where('booking_status', 'unpaid')
        ->update(['booking_status' => 'cancelled']);
        
         DB::table('hotel_room_bookings')
        ->join('bookings', 'hotel_room_bookings.booking_id', '=', 'bookings.id')
        ->where('bookings.booking_status', '-')
        ->delete();

        Booking::where('booking_status', '-')
        ->delete();
        // foreach ($unpaidInvoices as $invoice) {
           
        // }
        // $queries = DB::getQueryLog();
        // You can print or log the queries if needed
        // dd($queries);
    }
}
