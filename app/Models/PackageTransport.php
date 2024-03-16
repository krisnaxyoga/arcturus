<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTransport extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function transportdestination() {
        return $this->belongsTo(TransportDestination::class, 'destination');
    }

    public function agenttransport() {
        return $this->belongsTo(AgentTransport::class, 'transport_id');
    }

    public function ordertransport() {
        return $this->hasMany(OrderTransport::class);
    }
}
