<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePurchase extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contractrate() {
        return $this->belongsTo(ContractRate::class, 'contract_id');
    }

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function advancepurchaseprice() {
        return $this->hasMany(AdvancePurchasePrice::class);
    }
}
