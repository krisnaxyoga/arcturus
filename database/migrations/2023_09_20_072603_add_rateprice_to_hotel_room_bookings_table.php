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
        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            $table->integer('rate_price')->after('price')->nullable();
            $table->integer('total_ammount')->after('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            //
        });
    }
};
