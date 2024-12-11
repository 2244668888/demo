<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('more_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullable();
            // EMERGENCY CONTACT NAME
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->longText('emergency_contact_address')->nullable();
            $table->string('emergency_contact_phone_no')->nullable();

            $table->string('annual_leave')->nullable();
            $table->string('annual_leave_balance_day')->nullable();
            $table->string('carried_leave')->nullable();
            $table->string('carried_leave_balance_day')->nullable();
            $table->string('medical_leave')->nullable();
            $table->string('medical_leave_balance_day')->nullable();
            $table->string('unpaid_leave')->nullable();
            $table->string('unpaid_leave_balance_day')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('more_users');
    }
};
