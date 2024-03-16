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
            $table->text('other_policy')->after('cencellation_policy')->nullable();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->text('marketcountry')->after('country')->nullable();
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
