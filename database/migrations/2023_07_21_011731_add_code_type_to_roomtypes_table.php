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
        Schema::table('room_types', function (Blueprint $table) {
            $table->string('code')->after('name')->nullable();
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
        });

        Schema::table('contract_rates', function (Blueprint $table) {
            $table->string('tax_policy')->after('min_stay')->nullable();
            $table->string('service_policy')->after('min_stay')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            //
        });
    }
};
