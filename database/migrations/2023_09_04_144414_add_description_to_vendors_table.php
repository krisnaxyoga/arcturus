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
        Schema::table('vendors', function (Blueprint $table) {
            $table->text('description')->after('bank_account')->nullable();
            $table->text('highlight')->after('bank_account')->nullable();
            $table->decimal('markup_percent')->after('bank_account')->nullable();
            $table->string('type_property')->after('bank_account')->nullable();
            $table->integer('hotel_star')->after('bank_account')->nullable();
            $table->integer('review_score')->after('bank_account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
};
