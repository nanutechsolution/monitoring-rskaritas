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
        Schema::create('picu_devices', function (Blueprint $table) {
            $table->id();
            // Relasi ke lembar monitoring 24 jam
            $table->foreignId('picu_monitoring_id')
                  ->constrained('picu_monitorings')
                  ->onDelete('cascade');

            $table->string('nama_alat', 100); // Cth: ETT, CVC, Urin Kateter
            $table->string('ukuran', 50)->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->date('tanggal_pemasangan')->nullable();

            $table->char('petugas_id', 20)->nullable();
            $table->timestamps(); // Kita gunakan created_at sebagai waktu log
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picu_devices');
    }
};
