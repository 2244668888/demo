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
        Schema::create('call_for_assistances', function (Blueprint $table) {
            $table->id();
            $table->string('datetime')->nullable();
            $table->string('mc_no')->nullable();
            $table->string('call')->nullable();
            $table->string('package_no')->nullable();
            $table->string('msg_no')->nullable();
            $table->string('attended_datetime')->nullable();
            $table->string('attended_pic')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('submitted_datetime')->nullable();
            $table->string('status')->nullable();
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_for_assistances');
    }
};
