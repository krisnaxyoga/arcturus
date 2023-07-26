<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractPrice extends Model
{
    use HasFactory;

    public function room() {
        return $this->belongsTo(RoomHotel::class, 'room_id');
    }

    public function barprice() {
        return $this->belongsTo(BarPrice::class, 'barprice_id');
    }

    public function contractrate() {
        return $this->belongsTo(ContractRate::class, 'contract_id');
    }

    public function promotprice() {
        return $this->hasMany(PromoPrice::class);
    }

    public function hotelroombooking() {
        return $this->hasMany(HotelRoomBooking::class);
    }
}
