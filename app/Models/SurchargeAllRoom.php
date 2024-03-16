<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurchargeAllRoom extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
