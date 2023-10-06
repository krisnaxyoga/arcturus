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
        Schema::table('contract_rates', function (Blueprint $table) {
            
            $table->text('distribute')->after('cencellation_policy')->nullable();
            $table->text('except')->after('cencellation_policy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_rates', function (Blueprint $table) {
            //
        });
    }
};
