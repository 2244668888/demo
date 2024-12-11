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
        Schema::create('family_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('family_dob')->nullable();
            $table->string('family_age')->nullable();
            $table->string('family_phone')->nullable();
            $table->string('family_mobile')->nullable();
            $table->string('family_nric')->nullable();
            $table->string('family_passport')->nullable();
            $table->string('family_passport_expiry_date')->nullable();
            $table->string('family_immigration_no')->nullable();
            $table->string('family_immigration_no_expiry_date')->nullable();
            $table->string('family_permit_no')->nullable();
            $table->string('family_permit_no_expiry_date')->nullable();
            $table->string('children_no')->nullable();
            $table->longText('family_address')->nullable();
            $table->string('attachment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_users');
    }
};
