<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contractrate() {
        return $this->hasMany(ContractRate::class);
    }

    public function promotprice() {
        return $this->hasMany(PromoPrice::class);
    }

    public function booking() {
        return $this->hasMany(Booking::class);
    }

    public function hotelroombooking() {
        return $this->hasMany(HotelRoomBooking::class);
    }

    public function advancepurchase() {
        return $this->hasMany(AdvancePurchase::class);
    }

    public function historywallet() {
        return $this->hasMany(HistoryWallet::class);
    }
}
