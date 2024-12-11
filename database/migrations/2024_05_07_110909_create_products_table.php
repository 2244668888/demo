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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('part_no')->nullable();
            $table->string('part_name')->nullable();
            $table->unsignedBigInteger('type_of_product')->nullable();
            $table->foreign('type_of_product')->references('id')->on('type_of_products');
            $table->string('model')->nullable();
            $table->string('category')->nullable();
            $table->string('variance')->nullable();
            $table->longtext('description')->nullable();
            $table->string('moq')->nullable();
            $table->unsignedBigInteger('unit')->nullable();
            $table->foreign('unit')->references('id')->on('units');
            $table->string('part_weight')->nullable();
            $table->string('standard_packaging')->nullable();
            $table->string('customer_supplier')->nullable();
            $table->unsignedBigInteger('customer_name')->nullable();
            $table->foreign('customer_name')->references('id')->on('customers');
            $table->string('customer_product_code')->nullable();
            $table->unsignedBigInteger('supplier_name')->nullable();
            $table->foreign('supplier_name')->references('id')->on('suppliers');
            $table->string('supplier_product_code')->nullable();
            $table->string('have_bom')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
