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
        Schema::create('picu_blood_gas_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke lembar monitoring 24 jam
            $table->foreignId('picu_monitoring_id')
                ->constrained('picu_monitorings')
                ->onDelete('cascade');

            // Waktu aktual hasil AGD keluar atau diinput
            $table->dateTime('waktu_log');

            // Kolom dari form
            $table->decimal('guka_darah_bs', 5, 1)->nullable();
            $table->decimal('ph', 3, 2)->nullable();
            $table->decimal('pco2', 4, 1)->nullable();
            $table->decimal('po2', 4, 1)->nullable();
            $table->decimal('hco3', 4, 1)->nullable(); // Di form tertulis HCO2, asumsi HCO3
            $table->decimal('be', 4, 1)->nullable(); // Base Excess
            $table->decimal('sao2', 4, 1)->nullable();

            $table->char('petugas_id', 20)->nullable(); // NIP/ID perawat/analis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picu_blood_gas_logs');
    }
};
