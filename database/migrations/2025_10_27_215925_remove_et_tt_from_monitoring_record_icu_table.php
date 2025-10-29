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
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn('et_tt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->string('et_tt', 50)->nullable()->after('cuff_pressure'); // Sesuaikan posisi 'after'
        });
    }
};
