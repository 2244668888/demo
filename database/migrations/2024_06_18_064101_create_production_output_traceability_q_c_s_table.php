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
        Schema::create('production_output_traceability_q_c_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pot_id')->nullable();
            $table->foreign('pot_id')->references('id')->on('production_output_traceabilities')->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->foreign('rt_id')->references('id')->on('type_of_rejections')->nullable();
            $table->string('qty')->nullable();
            $table->longText('comments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_output_traceability_q_c_s');
    }
};
