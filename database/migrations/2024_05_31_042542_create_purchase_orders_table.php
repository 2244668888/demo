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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_id')->nullable();
            $table->foreign('pr_id')->references('id')->on('purchase_requisitions')->nullable();
            $table->unsignedBigInteger('pp_id')->nullable();
            $table->foreign('pp_id')->references('id')->on('purchase_plannings')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->nullable();
            $table->string('pp_pr')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('quotation_ref_no')->nullable();
            $table->string('date')->nullable();
            $table->string('attachment')->nullable();
            $table->string('payment_term')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->nullable();
            $table->longText('important_note')->nullable();
            $table->string('required_date')->nullable();
            $table->string('status')->nullable();
            $table->string('bulk_wo_discount')->nullable();
            $table->string('bulk_required_date')->nullable();

            $table->string('total_qty')->nullable();
            $table->string('total_sale_tax')->nullable();
            $table->string('total_discount')->nullable();
            $table->string('net_total')->nullable();

            $table->string('checked_by')->nullable();
            $table->string('checked_by_time')->nullable();
            $table->string('verify_by')->nullable();
            $table->string('verify_by_time')->nullable();
            $table->string('reason')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
