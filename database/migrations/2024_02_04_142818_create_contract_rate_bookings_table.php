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
        Schema::create('contract_rate_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('ratecode')->nullable();
            $table->string('codedesc')->nullable();
            $table->date('stayperiod_begin')->nullable();
            $table->date('stayperiod_end')->nullable();
            $table->date('booking_begin')->nullable();
            $table->date('booking_end')->nullable();
            $table->string('pick_day')->nullable();
            $table->integer('min_stay')->nullable();
            $table->text('benefit_policy')->nullable();
            $table->text('service_policy')->nullable();
            $table->text('tax_policy')->nullable();
            $table->text('deposit_policy')->nullable();
            $table->text('cencellation_policy')->nullable();
            $table->text('other_policy')->nullable();
            $table->text('except')->nullable();
            $table->text('distribute')->nullable();
            $table->integer('rolerate')->nullable();
            $table->integer('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_rate_bookings');
    }
};
