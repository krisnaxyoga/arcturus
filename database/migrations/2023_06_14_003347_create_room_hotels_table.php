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
        Schema::create('room_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('ratecode', 255)->nullable();
            $table->string('ratedesc', 255)->nullable();
            
            //Info
            $table->string('title', 255)->nullable();
            $table->string('feature_image', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('gallery', 255)->nullable();
            $table->string('video', 255)->nullable();
            $table->text('service_info')->nullable();
            $table->text('nearby_info')->nullable();
            $table->text('attribute')->nullable();

            //Price
            $table->Integer('room_allow')->nullable();
            $table->Integer('min_night')->nullable();
            $table->tinyInteger('size')->nullable();
            $table->tinyInteger('adults')->nullable();
            $table->tinyInteger('children')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_hotels');
    }
};
