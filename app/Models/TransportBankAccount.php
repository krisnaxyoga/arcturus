<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportBankAccount extends Model
{
    use HasFactory;

    public function agenttransport() {
        return $this->belongsTo(AgentTransport::class, 'transport_id');
    }
}
