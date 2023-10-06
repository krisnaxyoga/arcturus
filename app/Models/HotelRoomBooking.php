<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoomBooking extends Model
{
    use HasFactory;

    public function room() {
        return $this->belongsTo(RoomHotel::class, 'room_id');
    }

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function contractprice() {
        return $this->belongsTo(ContractPrice::class, 'contract_price_id');
    }

    public function contractrate() {
        return $this->belongsTo(ContractRate::class, 'contract_id');
    }
}
