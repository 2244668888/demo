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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longtext('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('group')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_telephone')->nullable();
            $table->string('contact_person_department')->nullable();
            $table->string('contact_person_mobile')->nullable();
            $table->string('contact_person_fax')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
