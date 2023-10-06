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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('logo_img')->nullable();
            $table->string('type_vendor'); //datanya berupa type json {"Hotel","Agent","Activities","Car","Boat",etc..)
            $table->string('vendor_legal_name',300)->nullable(); 
            $table->string('vendor_code',10)->nullable();
            $table->string('vendor_name',200)->nullable();
            $table->string('address_line1',255)->nullable();
            $table->string('address_line2',255)->nullable();
            $table->string('zip_code',20)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('email',100)->nullable();
            $table->string('web_uri')->nullable();
            $table->string('country',30)->nullable();
            $table->string('state',30)->nullable();
            $table->string('city',30)->nullable();
            $table->string('area',100)->nullable();
            $table->string('location',100)->nullable();
            $table->string('map_latitude',25)->nullable();
            $table->string('map_longitude',25)->nullable();
            $table->integer('map_zoom')->nullable();
            $table->decimal('credit_limit',13,2)->nullable();
            $table->decimal('credit_used',13,2)->nullable();
            $table->decimal('credit_saldo',13,2)->nullable();
            $table->integer('is_active')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
