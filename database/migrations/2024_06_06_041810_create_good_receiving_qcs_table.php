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
        Schema::create('good_receiving_qcs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gr_id')->nullable();
            $table->foreign('gr_id')->references('id')->on('good_receivings')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->foreign('rt_id')->references('id')->on('type_of_rejections')->nullable();
            $table->longText('qty')->nullable();
            $table->longText('comment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receiving_qcs');
    }
};
