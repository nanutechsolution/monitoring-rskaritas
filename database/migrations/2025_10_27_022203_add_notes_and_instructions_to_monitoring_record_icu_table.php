<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Kolom untuk Catatan Klinis / Masalah Keperawatan
            $table->text('clinical_note')->nullable()->after('cairan_keluar_volume');

            // Kolom untuk Instruksi Dokter / Tindakan / Obat
            $table->text('doctor_instruction')->nullable()->after('clinical_note');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn(['clinical_note', 'doctor_instruction']);
        });
    }
};
