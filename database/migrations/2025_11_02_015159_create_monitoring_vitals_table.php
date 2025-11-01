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
        Schema::create('monitoring_vitals', function (Blueprint $table) {
            $table->id();

            // Relasi ke form monitoring utama
            $table->foreignId('intra_anesthesia_monitoring_id')
                ->constrained('intra_anesthesia_monitorings')
                ->onDelete('cascade');

            $table->time('waktu'); // Waktu pencatatan

            // Kolom untuk grafik
            $table->integer('rrn')->nullable();      // Nadi [cite: 25]
            $table->integer('td_sis')->nullable();  // Tensi Sistolik [cite: 25]
            $table->integer('td_dis')->nullable();  // Tensi Diastolik [cite: 25]
            $table->integer('rr')->nullable();      // Respiratory Rate [cite: 25]
            $table->integer('spo2')->nullable();    // SpO2% [cite: 60]
            $table->integer('pe_co2')->nullable();  // PE CO2 [cite: 60]
            $table->integer('fio2')->nullable();    // FiO2 [cite: 60]
            $table->string('lain_lain')->nullable();// Lain-lain [cite: 60]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_vitals');
    }
};
