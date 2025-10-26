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
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            // Tambahkan 4 kolom baru setelah 'pemeriksaan_penunjang'
            $table->text('alat_terpasang')->nullable()->after('pemeriksaan_penunjang');
            $table->text('tube_terpasang')->nullable()->after('alat_terpasang');
            $table->text('masalah_keperawatan')->nullable()->after('tube_terpasang');
            $table->text('tindakan_obat')->nullable()->after('masalah_keperawatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn([
                'alat_terpasang',
                'tube_terpasang',
                'masalah_keperawatan',
                'tindakan_obat'
            ]);
        });
    }
};
