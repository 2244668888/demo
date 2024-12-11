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
        Schema::create('initail_nos', function (Blueprint $table) {
            $table->id();
            $table->string('screen')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('running_no')->nullable();
            $table->string('sample')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initail_nos');
    }
};
