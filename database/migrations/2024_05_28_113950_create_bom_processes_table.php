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
        Schema::create('bom_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_id')->nullable();
            $table->foreign('bom_id')->references('id')->on('boms');
            $table->string('process_id')->nullable();
            $table->string('process_no')->nullable();
            $table->string('raw_part_ids')->nullable();
            $table->string('sub_part_ids')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('machine_tonnage_id')->nullable();
            $table->string('cavity')->nullable();
            $table->string('ct')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_processes');
    }
};
