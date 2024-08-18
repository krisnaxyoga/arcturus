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
            $table->string('hotelid')->after('id')->nullable();
        });

        Schema::table('room_hotels', function (Blueprint $table) {
            $table->string('roomtypeid')->after('id')->nullable();
        });

        Schema::table('contract_rates', function (Blueprint $table) {
            $table->string('ratecodeid')->after('id')->nullable();
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
