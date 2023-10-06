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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->date('booking_date')->nullable();
            $table->date('checkin_date')->nullable();
            $table->date('checkout_date')->nullable();
            $table->integer('night')->nullable();
            $table->integer('adult')->nullable();
            $table->integer('children')->nullable();
            $table->integer('total_guests')->nullable();
            $table->integer('price')->nullable();
            $table->integer('deposit')->nullable();
            $table->string('booking_status', 30)->nullable();
            $table->integer('payment_method')->nullable();
            $table->string('deposit_type', 30)->nullable();
            $table->integer('commission')->nullable();
            $table->string('commission_type', 150)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip_code', 15)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('special_request')->nullable();
            $table->decimal('vendor_service_fee_amount', 8, 2)->nullable();
            $table->text('vendor_service_fee')->nullable();
            $table->text('buyer_fees')->nullable();
            $table->integer('total_before_fees')->nullable();
            $table->integer('coupon_amount')->nullable();
            $table->integer('total_before_discount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
