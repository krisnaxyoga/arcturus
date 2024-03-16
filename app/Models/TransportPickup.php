<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportPickup extends Model
{
    use HasFactory;
    public function agenttransport() {
        return $this->belongsTo(AgentTransport::class, 'transport_id');
    }

    public function ordertransport() {
        return $this->belongsTo(OrderTransport::class, 'ordertransport_id');
    }
}
