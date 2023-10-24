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
        Schema::table('hotel_room_surcharges', function (Blueprint $table) {
            $table->integer('night')->after('price')->default(1)->nullable();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->integer('recomend')->after('zip_code')->default(2000)->nullable();
        });

        Schema::table('contract_prices', function (Blueprint $table) {
            $table->integer('recomend')->after('price')->default(2000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_room_surcharges', function (Blueprint $table) {
            //
        });
    }
};
