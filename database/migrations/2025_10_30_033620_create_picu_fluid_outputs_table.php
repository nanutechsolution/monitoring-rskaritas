<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('picu_fluid_outputs', function (Blueprint $table) {
            $table->id();
            // Relasi ke lembar monitoring 24 jam
            $table->foreignId('picu_monitoring_id')
                  ->constrained('picu_monitorings')
                  ->onDelete('cascade');

            $table->dateTime('waktu_log');

            // Kategori: NGT, URINE, BAB, DRAIN
            $table->string('kategori', 50);

            // Keterangan (Warna urine, konsistensi BAB, dll)
            $table->string('keterangan', 100)->nullable();

            // Jumlah dalam ml
            $table->decimal('jumlah', 8, 2);

            $table->char('petugas_id', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('picu_fluid_outputs');
    }
};
