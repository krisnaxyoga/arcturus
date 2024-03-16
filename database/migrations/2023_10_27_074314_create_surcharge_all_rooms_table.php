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
        Schema::create('surcharge_all_rooms', function (Blueprint $table) {
            $table->id();  
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->integer('surcharge_price')->nullable();
            $table->date('stayperiod_start')->nullable();
            $table->date('stayperiod_end')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surcharge_all_rooms');
    }
};
