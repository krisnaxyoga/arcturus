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
        Schema::create('contract_rate_price_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('room_name')->nullable();
            $table->integer('size')->nullable();
            $table->integer('extra_bed')->nullable();
            $table->integer('price')->nullable();
            $table->integer('recom_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_rate_price_bookings');
    }
};
