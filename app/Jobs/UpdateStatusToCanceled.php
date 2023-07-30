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
        $unpaidInvoices = Booking::where('status', 'unpaid')
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->get();

        foreach ($unpaidInvoices as $invoice) {
            $invoice->update(['status' => 'canceled']);
        }
    }
}
