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
        Schema::table('contract_prices', function (Blueprint $table) {
            $table->integer('is_active')->after('price')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('saldo')->after('is_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_prices', function (Blueprint $table) {
            //
        });
    }
};
