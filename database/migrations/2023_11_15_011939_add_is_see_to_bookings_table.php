<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            
            $table->integer('is_see')->after('total_before_discount')->nullable();
        });

        Schema::table('payment_getway_transactions', function (Blueprint $table) {
            
            $table->integer('is_see')->after('trx_id')->nullable();
        });

        Schema::table('widraw_vendors', function (Blueprint $table) {
            
            $table->integer('is_see')->after('image')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            
            $table->integer('is_see')->after('saldo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
