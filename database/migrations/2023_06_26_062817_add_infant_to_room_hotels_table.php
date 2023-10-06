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
        Schema::table('room_hotels', function (Blueprint $table) {
            $table->integer('infant')->nullable();
            $table->integer('extra_bed')->nullable();
            $table->integer('baby_cot')->nullable();
            $table->integer('bedroom')->nullable();
            $table->integer('max_benefit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_hotels', function (Blueprint $table) {
            //
        });
    }
};
