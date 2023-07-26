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
        Schema::create('promo_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('contract_price_id');
            $table->unsignedBigInteger('room_id');
            $table->integer('price')->nullable();
            $table->integer('days')->nullable();
            $table->integer('min_stay')->nullable();
            $table->text('distribute')->nullable();
            $table->text('except')->nullable();
            $table->date('start_periods')->nullable();
            $table->date('end_periods')->nullable();
            $table->date('start_booking')->nullable();
            $table->date('end_booking')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_prices');
    }
};
