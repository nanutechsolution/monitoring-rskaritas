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
        Schema::create('monitoring_medications', function (Blueprint $table) {
            $table->id();

            // Relasi ke form monitoring utama
            $table->foreignId('intra_anesthesia_monitoring_id')
                ->constrained('intra_anesthesia_monitorings')
                ->onDelete('cascade');
            $table->time('waktu'); // Waktu pemberian
            $table->string('nama_obat_infus_gas'); // [cite: 19]
            $table->string('dosis'); // String agar fleksibel (cth: "10mg" atau "5mcg/kg/min")
            $table->string('rute')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_medications');
    }
};
