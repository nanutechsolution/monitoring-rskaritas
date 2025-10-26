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
        Schema::create('monitoring_cycle_icu', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 17)->charset('latin1');
            $table->date('sheet_date');
            $table->text('diagnosa')->nullable();
            $table->string('asal_ruangan', 100)->nullable();
            $table->integer('hari_rawat_ke')->nullable();
            // Data Kiri Bawah (Statis 24 Jam)
            $table->text('catatan_lain_lain')->nullable();
            $table->text('terapi_obat_parenteral')->nullable();
            $table->text('terapi_obat_enteral_lain')->nullable();
            $table->text('pemeriksaan_penunjang')->nullable();
            // Data Kalkulasi (Hasil akhir siklus 24 jam)
            $table->decimal('daily_iwl', 8, 2)->nullable();
            $table->decimal('calculated_balance_24h', 10, 2)->nullable();
            // Waktu Siklus (cth: 07:00 hari ini s/d 07:00 besok)
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->timestamps();
            // Relasi & Index
            // Asumsi ada tabel 'reg_periksa' dengan kolom 'no_rawat'
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->cascadeOnDelete();
            $table->index(['no_rawat', 'sheet_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_cycle_icu');
    }
};
