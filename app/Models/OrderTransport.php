<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransport extends Model
{
    use HasFactory;
    
    public function agenttransport() {
        return $this->belongsTo(AgentTransport::class, 'transport_id');
    }

    public function agent() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function package() {
        return $this->belongsTo(PackageTransport::class, 'package_id');
    }
}
