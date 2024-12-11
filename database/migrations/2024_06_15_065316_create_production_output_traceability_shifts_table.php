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
        Schema::create('production_output_traceability_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dpp_id')->nullable();
            $table->foreign('dpp_id')->references('id')->on('daily_production_plannings')->nullable();
            $table->unsignedBigInteger('pot_id')->nullable();
            $table->foreign('pot_id')->references('id')->on('production_output_traceabilities')->nullable();
            $table->string('time')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('good_qty')->nullable();
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_output_traceability_shifts');
    }
};
