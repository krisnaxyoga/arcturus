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
            $table->integer('system_markup')->after('credit_saldo')->nullable();
            $table->string('bank_account')->after('credit_saldo')->nullable();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('total_room')->after('price')->nullable();
        });

        Schema::table('hotel_room_bookings', function (Blueprint $table) {
            $table->integer('total_room')->after('price')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
};
