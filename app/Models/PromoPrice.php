<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoPrice extends Model
{
    use HasFactory;
    public function room() {
        return $this->belongsTo(RoomHotel::class, 'room_id');
    }

    public function contractprice() {
        return $this->belongsTo(BarPrice::class, 'contract_price_id');
    }

    public function vendor() {
        return $this->belongsTo(BarPrice::class, 'vendor_id');
    }

    public function contractrate() {
        return $this->belongsTo(ContractRate::class, 'contract_id');
    }
    
}
