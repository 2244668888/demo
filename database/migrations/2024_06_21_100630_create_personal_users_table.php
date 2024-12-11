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
        Schema::create('personal_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('dob')->nullable();
            $table->string('age')->nullable();
            $table->string('personal_phone')->nullable();
            $table->string('personal_mobile')->nullable();
            $table->string('nric')->nullable();
            $table->string('nationality')->nullable();
            $table->string('epf_no')->nullable();
            $table->string('sosco_no')->nullable();
            $table->string('tin')->nullable();
            $table->bigInteger('base_salary')->nullable();
            $table->string('passport')->nullable();
            $table->string('passport_expiry_date')->nullable();
            $table->string('immigration_no')->nullable();
            $table->string('immigration_no_expiry_date')->nullable();
            $table->string('permit_no')->nullable();
            $table->string('permit_no_expiry_date')->nullable();
            $table->longText('address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_users');
    }
};
