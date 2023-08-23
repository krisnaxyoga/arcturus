<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePurchasePrice extends Model
{
    use HasFactory;

    public function room() {
        return $this->belongsTo(RoomHotel::class, 'room_id');
    }

    public function contractrate() {
        return $this->belongsTo(ContractRate::class, 'contract_id');
    }

}
