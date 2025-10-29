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
        Schema::create('picu_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 17)->index();

            // Waktu mulai dan selesai lembar observasi (sesuai diskusi kita)
            $table->dateTime('start_datetime')->comment('Cth: 2025-10-30 06:00:00');
            $table->dateTime('end_datetime')->comment('Cth: 2025-10-31 05:59:59');

            $table->char('dokter_dpjp', 20)->nullable(); // Foreign key ke dokter
            $table->string('diagnosis', 100)->nullable();

            // Summary Balance Cairan 24 Jam
            $table->decimal('balance_cairan_24h_sebelumnya', 8, 2)->nullable();
            $table->decimal('total_cairan_masuk_24h', 8, 2)->nullable();
            $table->decimal('total_cairan_keluar_24h', 8, 2)->nullable();
            $table->decimal('produksi_urine_24h', 8, 2)->nullable();
            $table->decimal('ewl_24h', 8, 2)->nullable();
            $table->decimal('balance_cairan_24h', 8, 2)->nullable();

            $table->unique(['no_rawat', 'start_datetime']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picu_monitorings');
    }
};
