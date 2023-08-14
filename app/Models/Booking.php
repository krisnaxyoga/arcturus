<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function hotelroombooking() {
        return $this->hasMany(HotelRoomBooking::class);
    }

    public function paymentgetwaytransaction() {
        return $this->hasMany(PaymentGetwayTransaction::class);
    }

}
