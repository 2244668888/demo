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
        Schema::create('material_requisition_product_details_receives', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();
            $table->string('rack')->nullable();
            $table->string('level')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('qty')->nullable();
            $table->string('mrf_detail_id')->nullable();
            $table->string('product_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requisition_product_details_receives');
    }
};