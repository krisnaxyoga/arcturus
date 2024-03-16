<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackoutContractRate extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function contract() {
        return $this->belongsTo(ContractRate::class, 'contract_id');
    }
}
