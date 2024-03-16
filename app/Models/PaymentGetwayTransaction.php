<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGetwayTransaction extends Model
{
    use HasFactory;

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
