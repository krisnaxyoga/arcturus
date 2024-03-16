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
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('agentmarkup')->after('total_before_fees')->nullable();
            $table->integer('noagentmarkup')->after('total_before_fees')->nullable();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->decimal('agent_markup')->after('system_markup')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
