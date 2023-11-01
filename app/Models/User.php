<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
     // inverse one to Many ke tabel role
     public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function vendor() {
        return $this->hasMany(Vendor::class);
    }

    public function booking() {
        return $this->hasMany(Booking::class);
    }

    public function vendors() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function advancepurchaseprice() {
        return $this->hasMany(AdvancePurchasePrice::class);
    }

    public function advancepurchase() {
        return $this->hasMany(AdvancePurchase::class);
    }

    public function historywallet() {
        return $this->hasMany(HistoryWallet::class);
    }

    public function paymentgetwaytransaction() {
        return $this->hasMany(PaymentGetwayTransaction::class);
    }

    public function surchargeAllRoom() {
        return $this->hasMany(SurchargeAllRoom::class);
    }
}
