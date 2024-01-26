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
        Schema::table('transport_destinations', function (Blueprint $table) {
            $table->string('country')->after('destination')->nullable();
            $table->string('state')->after('destination')->nullable();
            $table->string('city')->after('destination')->nullable();
        });

        Schema::table('agent_transports', function (Blueprint $table) {
            $table->string('country')->after('password')->nullable();
            $table->string('state')->after('password')->nullable();
            $table->string('city')->after('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transport_destinations', function (Blueprint $table) {
            //
        });
    }
};
