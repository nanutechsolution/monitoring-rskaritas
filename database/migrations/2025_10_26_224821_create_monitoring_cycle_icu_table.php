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
        // Ini adalah tabel ANAK untuk menyimpan data PER JAM
        Schema::create('monitoring_record_icu', function (Blueprint $table) {
            $table->id();

            // Kunci ke Tabel Induk 'monitoring_cycles'
            $table->foreignId('monitoring_cycle_icu_id')
                ->constrained('monitoring_cycle_icu') // Mengacu ke tabel induk
                ->cascadeOnDelete();

            // Jam observasi (cth: jam 8, 9, 10)
            $table->datetime('observation_time');

            // Pengguna yang menginput
            $table->string('nik_inputter', 20)->charset('latin1')->nullable();
            $table->foreign('nik_inputter')
                ->references('nik')
                ->on('pegawai')
                ->nullOnDelete();
            // --- HEMODINAMIK ---
            $table->decimal('suhu', 4, 2)->nullable();
            $table->unsignedSmallInteger('nadi')->nullable();
            $table->string('ekg', 50)->nullable();
            $table->unsignedSmallInteger('tensi_sistol')->nullable();
            $table->unsignedSmallInteger('tensi_diastol')->nullable();
            $table->unsignedSmallInteger('map')->nullable();

            // --- RESPIRASI ---
            $table->unsignedSmallInteger('spo2')->nullable();
            $table->unsignedSmallInteger('rr')->nullable();
            $table->string('ventilator_mode', 50)->nullable();
            $table->unsignedSmallInteger('ventilator_f')->nullable();
            $table->unsignedSmallInteger('ventilator_tv')->nullable();
            $table->unsignedSmallInteger('ventilator_fio2')->nullable(); // Dalam %
            $table->unsignedSmallInteger('ventilator_peep')->nullable();
            $table->string('et_tt', 50)->nullable(); // ET / TT
            $table->smallInteger('cvp')->nullable(); // CVP bisa negatif

            // --- OBSERVASI ---
            $table->string('pupil_kiri_ukuran', 20)->nullable();
            $table->string('pupil_kiri_reaksi', 20)->nullable();
            $table->string('pupil_kanan_ukuran', 20)->nullable();
            $table->string('pupil_kanan_reaksi', 20)->nullable();
            $table->unsignedTinyInteger('gcs_e')->nullable(); // 1-4
            $table->unsignedTinyInteger('gcs_v')->nullable(); // 1-5
            $table->unsignedTinyInteger('gcs_m')->nullable(); // 1-6
            $table->unsignedTinyInteger('gcs_total')->nullable(); // 3-15
            $table->string('kesadaran', 50)->nullable(); // CM, Apatis, dll
            $table->unsignedTinyInteger('nyeri')->nullable(); // Skala 0-10
            $table->string('sedasi', 50)->nullable(); // e.g., RASS

            // --- CAIRAN MASUK (per jam) ---
            $table->string('cairan_masuk_jenis', 100)->nullable();
            $table->unsignedSmallInteger('cairan_masuk_volume')->nullable();
            // --- CAIRAN KELUAR (per jam) ---
            $table->string('cairan_keluar_jenis', 100)->nullable();
            $table->unsignedSmallInteger('cairan_keluar_volume')->nullable();

            $table->timestamps();
            $table->index('observation_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_record_icu');
    }
};
