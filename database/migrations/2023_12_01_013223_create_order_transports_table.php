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
        Schema::create('order_transports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transport_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('guest_name')->nullable();
            $table->string('phone_guest')->nullable();
            $table->string('time_pickup')->nullable();
            $table->date('pickup_date')->nullable();
            $table->integer('total_price')->nullable();
            $table->string('destination')->nullable();
            $table->string('typecar')->nullable();
            $table->string('number_police')->nullable();
            $table->integer('total_price_nomarkup')->nullable();
            $table->text('pickup_confirmation')->nullable();
            $table->string('booking_status', 30)->nullable();
            $table->integer('is_see')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transports');
    }
};
