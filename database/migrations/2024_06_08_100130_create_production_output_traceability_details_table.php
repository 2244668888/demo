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
        Schema::create('production_output_traceability_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dpp_id')->nullable();
            $table->foreign('dpp_id')->references('id')->on('daily_production_plannings')->nullable();
            $table->unsignedBigInteger('pot_id')->nullable();
            $table->foreign('pot_id')->references('id')->on('production_output_traceabilities')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->foreign('machine_id')->references('id')->on('machines')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('duration')->nullable();
            $table->string('operator')->nullable();
            $table->string('count')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_output_traceability_details');
    }
};
