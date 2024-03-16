<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAffiliate extends Model
{
    use HasFactory;

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function affiliate() {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }
}
