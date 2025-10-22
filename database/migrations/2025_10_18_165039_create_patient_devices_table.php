<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_devices', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 17); // Link ke registrasi perawatan
            $table->string('device_name');  // Nama alat (PICC, CVC, ETT, etc.)
            $table->string('size')->nullable(); // Ukuran
            $table->string('location')->nullable(); // Lokasi pemasangan
            $table->date('installation_date')->nullable(); // Tanggal Pemasangan
            // $table->date('removal_date')->nullable(); // Opsional: Tanggal Pelepasan
            $table->timestamps();

            $table->index('no_rawat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_devices');
    }
};
