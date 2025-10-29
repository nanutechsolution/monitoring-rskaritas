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
        Schema::create('picu_medication_logs', function (Blueprint $table) {
            $table->id();
            // Relasi ke lembar monitoring 24 jam
            $table->foreignId('picu_monitoring_id')
                  ->constrained('picu_monitorings')
                  ->onDelete('cascade');

            $table->dateTime('waktu_pemberian');
            $table->string('nama_obat', 150);
            $table->string('dosis', 50); // Dibuat string karena bisa '2x50mg' atau '10mcg/kg/min'
            $table->string('rute', 50); // Cth: IV, PO, IM, Subkutan

            $table->char('petugas_id', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picu_medication_logs');
    }
};
