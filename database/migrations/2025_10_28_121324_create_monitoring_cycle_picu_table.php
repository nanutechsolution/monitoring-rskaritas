<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_cycle_picu', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 17)->charset('latin1');
            $table->date('sheet_date');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('hari_rawat_ke')->nullable();

            // --- Field Header KHUSUS PICU ---
            $table->string('umur_bayi_anak', 50)->nullable();
            $table->string('cara_persalinan', 100)->nullable();
            // --- Akhir Field PICU ---

            // Catatan Pola Ventilasi Harian
            $table->text('ventilator_notes')->nullable();

            // Target Nutrisi Harian
            $table->decimal('enteral_target_volume', 8, 2)->nullable();
            $table->integer('enteral_target_kalori')->nullable();
            $table->integer('enteral_target_protein')->nullable();
            $table->integer('enteral_target_lemak')->nullable();
            $table->decimal('parenteral_target_volume', 8, 2)->nullable();
            $table->integer('parenteral_target_kalori')->nullable();
            $table->integer('parenteral_target_protein')->nullable();
            $table->integer('parenteral_target_lemak')->nullable();

            // Textarea Data Statis Lain
            $table->text('terapi_obat_parenteral')->nullable();
            $table->text('terapi_obat_enteral_lain')->nullable();
            $table->text('pemeriksaan_penunjang')->nullable();
            $table->text('catatan_lain_lain')->nullable();
            $table->text('wound_notes')->nullable(); // Catatan Luka

            // Kalkulasi Balance Cairan
            $table->decimal('daily_iwl', 8, 2)->nullable();
            $table->decimal('calculated_balance_24h', 10, 2)->nullable();
            $table->decimal('previous_balance', 10, 2)->nullable()->default(0);

            $table->timestamps();

            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->cascadeOnDelete();
            $table->index(['no_rawat', 'sheet_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_cycle_picu');
    }
};
