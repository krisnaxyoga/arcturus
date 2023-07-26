<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRate extends Model
{
    use HasFactory;

    protected $casts = [
        'pick_day' => 'array',
        'distribute' => 'array',
        'except' => 'array'
    ];

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function contractprice() {
        return $this->hasMany(ContractPrice::class);
    }

    public function promotprice() {
        return $this->hasMany(PromoPrice::class);
    }

    public function hotelroombooking() {
        return $this->hasMany(HotelRoomBooking::class);
    }
}
