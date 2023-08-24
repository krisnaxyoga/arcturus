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
            $table->integer('pricenomarkup')->after('price')->nullable();
        });

        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            $table->integer('pricenomarkup')->after('price')->nullable();
        });

        Schema::table('contract_rates', function (Blueprint $table) {
            $table->text('benefit_policy')->after('min_stay')->nullable();
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
