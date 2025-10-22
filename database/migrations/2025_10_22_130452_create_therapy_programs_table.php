<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // 1. Buat tabel baru 'therapy_programs'
        Schema::create('therapy_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitoring_cycle_id');
            $table->string('no_rawat', 17);
            $table->string('id_user', 700);
            $table->text('program_text');
            $table->timestamps(); // Ini akan membuat created_at dan updated_at

            // Definisikan foreign key
            $table->foreign('monitoring_cycle_id')
                  ->references('id')
                  ->on('monitoring_cycles')
                  ->onDelete('cascade'); // Jika cycle dihapus, riwayatnya ikut terhapus

            // Tambahkan index untuk pencarian cepat
            $table->index('no_rawat');
        });

        // 2. Modifikasi tabel 'monitoring_cycles' untuk hapus kolom lama
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->dropColumn(columns: 'therapy_program');
            $table->dropColumn('clinical_problems');
        });
    }

    /**
     * Balikkan migrasi (jika terjadi rollback).
     */
    public function down(): void
    {
        // 1. Hapus tabel 'therapy_programs'
        Schema::dropIfExists('therapy_programs');

        // 2. Kembalikan kolom lama ke 'monitoring_cycles'
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->text('therapy_program')->nullable();
            $table->text('clinical_problems')->nullable();
        });
    }
};
