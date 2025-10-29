<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn(['masalah_keperawatan', 'tindakan_obat']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->text('masalah_keperawatan')->nullable()->after('tube_terpasang');
            $table->text('tindakan_obat')->nullable()->after('masalah_keperawatan');
        });
    }
};
