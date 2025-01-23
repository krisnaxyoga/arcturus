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
        Schema::table('package_transports', function (Blueprint $table) {
            $table->text('other_policy')->after('price')->nullable();
            // $table->text('change_policy')->after('price')->nullable();
            // $table->text('cancellation_policy')->after('price')->nullable();
            $table->text('description')->after('price')->nullable();
            $table->integer('set')->after('price')->nullable();
        });

        Schema::table('order_transports', function (Blueprint $table) {
            $table->integer('set')->after('typecar')->nullable();
            $table->text('other_policy')->after('typecar')->nullable();
            $table->text('description')->after('typecar')->nullable();
            $table->text('change_policy')->after('typecar')->nullable();
            $table->text('cancellation_policy')->after('typecar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_transports', function (Blueprint $table) {
            //
        });
    }
};