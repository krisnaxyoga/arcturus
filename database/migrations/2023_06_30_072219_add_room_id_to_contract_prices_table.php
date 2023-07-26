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
        Schema::table('contract_prices', function (Blueprint $table) {
            $table->integer('recom_price')->nullable()->after('price');
            $table->Integer('price')->nullable()->change();
            $table->unsignedBigInteger('room_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_prices', function (Blueprint $table) {
            //
        });
    }
};
