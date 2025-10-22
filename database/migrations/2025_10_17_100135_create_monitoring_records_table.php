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
        Schema::create('monitoring_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_cycle_id')->constrained('monitoring_cycles')->onDelete('cascade');
            $table->string('id_user', 700);
            $table->dateTime('record_time');

            // --- PENYEMPURNAAN TIPE DATA ---

            // Untuk angka dengan koma (desimal)
            $table->decimal('temp_incubator', 4, 1)->nullable(); // Contoh: 37.5
            $table->decimal('temp_skin', 4, 1)->nullable();      // Contoh: 36.8

            // Untuk angka bulat (integer)
            $table->unsignedSmallInteger('hr')->nullable();          // Contoh: 130
            $table->unsignedSmallInteger('rr')->nullable();          // Contoh: 45
            $table->unsignedSmallInteger('blood_pressure_systolic')->nullable();
            $table->unsignedSmallInteger('blood_pressure_diastolic')->nullable();
            $table->unsignedSmallInteger('sat_o2')->nullable();      // Contoh: 98

            // String cocok untuk data yang bisa berisi teks atau simbol
            $table->string('fio2_o2')->nullable();           // Contoh: "2L" atau "40%"
            $table->string('pain_scale')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_records');
    }
};
