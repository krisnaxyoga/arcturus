<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomHotel extends Model
{
    use HasFactory;
    protected $casts = [
        'attribute' => 'array',
        'max_adult' => 'array',
        'max_child' => 'array',
        'gallery' => 'array'
    ];

    public function childpriceroom() {
        return $this->hasMany(ChildPriceRoom::class);
    }

    public function adultpriceroom() {
        return $this->hasMany(AdultPriceRoom::class);
    }

    public function mothroompromotion() {
        return $this->hasMany(MothRoomPromotion::class);
    }

    public function barprice() {
        return $this->hasMany(BarPrice::class);
    }

    public function contractprice() {
        return $this->hasMany(ContractPrice::class);
    }

    public function hotelroombooking() {
        return $this->hasMany(HotelRoomBooking::class);
    }

    public function promotprice() {
        return $this->hasMany(PromoPrice::class);
    }

    public function advancepurchaseprice() {
        return $this->hasMany(AdvancePurchasePrice::class);
    }

    public function extrabedprice(){
        return $this->hasOne(ExtrabedPrice::class, 'room_id');
    }
}
