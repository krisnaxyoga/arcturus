<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtrabedPrice extends Model
{
    use HasFactory;

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function roomhotel() {
        return $this->belongsTo(RoomHotel::class, 'room_id');
    }
}
