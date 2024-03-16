<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentTransport extends Authenticatable implements JWTSubject
{
    protected $table = 'agent_transports';

    protected $fillable = [
        'email',
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function packageTransport() {
        return $this->hasMany(PackageTransport::class);
    }

    public function ordertransport() {
        return $this->hasMany(OrderTransport::class, 'transport_id');
    }

    public function transportBankAccount() {
        return $this->hasOne(TransportBankAccount::class, 'transport_id');
    }

    public function widrawtransport() {
        return $this->hasOne(WidrawTransport::class, 'transport_id');
    }
}
