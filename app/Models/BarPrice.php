<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarPrice extends Model
{
    use HasFactory;
    public function barroom() {
        return $this->belongsTo(BarRoom::class, 'bar_id');
    }

    public function room() {
        return $this->belongsTo(RoomHotel::class, 'room_id');
    }

    public function contractprice() {
        return $this->hasMany(ContractPrice::class);
    }

}
