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
        Schema::table('vendors', function (Blueprint $table) {
            $table->integer('markupbed')->after('is_active')->nullable();
        });

        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            $table->integer('priceextrabed')->after('price')->nullable();
            $table->integer('total_bed')->after('price')->nullable();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('priceextrabed')->after('deposit')->nullable();
            $table->integer('total_bed')->after('deposit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
