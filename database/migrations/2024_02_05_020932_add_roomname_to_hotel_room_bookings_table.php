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
            $table->string('room_name')->after('price')->nullable();
            $table->string('contract_name')->after('price')->nullable();
            $table->text('benefit_policy')->after('price')->nullable();
            $table->text('other_policy')->after('price')->nullable();
            $table->text('cencellation_policy')->after('price')->nullable();
            $table->text('deposit_policy')->after('price')->nullable();
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
