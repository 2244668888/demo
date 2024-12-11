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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->longtext('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_department')->nullable();
            $table->string('pic_phone_work')->nullable();
            $table->string('pic_phone_mobile')->nullable();
            $table->string('pic_fax')->nullable();
            $table->string('pic_email')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
