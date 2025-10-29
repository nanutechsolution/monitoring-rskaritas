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
        Schema::create('picu_cycles', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel induk
            $table->foreignId('picu_monitoring_id')
                  ->constrained('picu_monitorings')
                  ->onDelete('cascade');

            // Waktu input "Real-Time"
            $table->dateTime('waktu_observasi')->comment('Waktu aktual perawat input data');

            // Slot jam di grid (untuk Tampilan 2: Review)
            $table->tinyInteger('jam_grid')->comment('Slot jam di grid (6, 7, ... 23, 0, ... 5)');

            // Tanda Vital
            $table->decimal('temp_inkubator', 4, 1)->nullable();
            $table->decimal('temp_skin', 4, 1)->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->string('tekanan_darah', 10)->nullable(); // cth: "120/80"
            $table->integer('sat_o2')->nullable();
            $table->string('irama_ekg', 50)->nullable();
            $table->tinyInteger('skala_nyeri')->nullable();
            $table->string('huidifier_inkubator', 50)->nullable();

            // Observasi Apnea Warna
            $table->boolean('cyanosis')->nullable();
            $table->boolean('pucat')->nullable();
            $table->boolean('icterus')->nullable();
            $table->boolean('crt_lt_2')->nullable(); // CRT < 2 detik
            $table->boolean('bradikardia')->nullable();
            $table->boolean('stimulasi')->nullable();

            $table->char('petugas_id', 20)->nullable(); // NIP/ID perawat yang input

            // 1 entry per slot jam per lembar monitoring
            $table->unique(['picu_monitoring_id', 'jam_grid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picu_cycles');
    }
};
