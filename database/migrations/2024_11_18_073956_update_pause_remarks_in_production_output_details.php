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
        Schema::table('production_output_traceability_details', function (Blueprint $table) {
             $table->string('pause_remarks')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_output_traceability_details', function (Blueprint $table) {
            $table->dropColumn('pause_remarks');
        });
    }
};
