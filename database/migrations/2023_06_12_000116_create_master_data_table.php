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
        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            $table->date('stay_date')->nullable();
            $table->string('confirmation_no')->nullable();
            $table->integer('room_night')->nullable();
            $table->string('rate_desc')->nullable();
            $table->string('special_code')->nullable();
            $table->string('promotion')->nullable();
            $table->string('market_code')->nullable();
            $table->string('market_group')->nullable();
            $table->string('rate_code')->nullable();
            $table->integer('rate_amount')->nullable();
            $table->integer('adult')->nullable();
            $table->integer('children')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('source_name')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('out_of_order')->nullable();
            $table->integer('out_of_service')->nullable();
            $table->string('room_no')->nullable();
            $table->integer('group_status')->nullable();
            $table->string('resv_status')->nullable();
            $table->integer('packages')->nullable();
            $table->string('title')->nullable();
            $table->string('passport')->nullable();
            $table->string('localid')->nullable();
            $table->date('cencelation_date')->nullable();
            $table->date('insert_date')->nullable();
            $table->integer('lead_days')->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->integer('length_of_stay')->nullable();
            $table->string('room_type_charge')->nullable();
            $table->string('room_type')->nullable();
            $table->string('booked_room_class')->nullable();
            $table->string('res_alt')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('member_id')->nullable();
            $table->date('member_enroll_date')->nullable();
            $table->string('member_level')->nullable();
            $table->string('member_enroll')->nullable();
            $table->string('winner_circle_club_id')->nullable();
            $table->string('winner_circle_club')->nullable();
            $table->string('signature_club_id')->nullable();
            $table->string('signature_club_enroll')->nullable();
            $table->date('birthday')->nullable();
            $table->string('mailist')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->integer('room_dev_usd')->nullable();
            $table->integer('fb_rev_usd')->nullable();
            $table->integer('outher_rev_usd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
