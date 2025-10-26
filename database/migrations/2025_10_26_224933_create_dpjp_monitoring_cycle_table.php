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
        Schema::create('dpjp_monitoring_cycle_icu', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel header observasi
            $table->foreignId('monitoring_cycle_icu_id')
                ->constrained('monitoring_cycle_icu')
                ->cascadeOnDelete();

            $table->string('kd_dokter', 20)->charset('latin1');
            $table->foreign('kd_dokter')
                ->references('kd_dokter')
                ->on('dokter')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(
                ['monitoring_cycle_icu_id', 'kd_dokter'],
                'dpjp_cycle_dokter_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dpjp_monitoring_cycle_icu');
    }
};
