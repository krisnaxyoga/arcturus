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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('title',10)->nullable();
            $table->string('first_name',200)->nullable();
            $table->string('last_name',200)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile_phone',30)->nullable();
            $table->string('profile_image')->nullable();
            $table->string('departement')->nullable();
            $table->string('position')->nullable();
            // tambahan untuk role id ditable role
            $table->unsignedBigInteger('role_id');
            $table->integer('is_primary_contact')->nullable();
            $table->integer('is_active')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
