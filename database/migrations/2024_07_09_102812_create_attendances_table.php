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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->string('name')->nullable();
            $table->string('auto_assign')->nullable();
            $table->string('absent')->nullable();
            $table->string('date')->nullable();
            $table->string('timetable')->nullable();
            $table->string('on_duty')->nullable();
            $table->string('off_duty')->nullable();
            $table->string('clock_in')->nullable();
            $table->string('clock_out')->nullable();
            $table->string('normal')->nullable();
            $table->string('real_time')->nullable();
            $table->string('late')->nullable();
            $table->string('early')->nullable();
            $table->string('ot_time')->nullable();
            $table->string('work_time')->nullable();
            $table->string('exception')->nullable();
            $table->string('department')->nullable();
            $table->string('n_days')->nullable();
            $table->string('week_end')->nullable();
            $table->string('holiday')->nullable();
            $table->string('att_time')->nullable();
            $table->string('n_days_ot')->nullable();
            $table->string('weekend_ot')->nullable();
            $table->string('holiday_ot')->nullable();
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
        Schema::dropIfExists('attendances');
    }
};
