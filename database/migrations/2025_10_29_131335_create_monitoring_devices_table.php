<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_cycle_icu_id')
                  ->constrained('monitoring_cycle_icu') // Link ke lembar observasi
                  ->cascadeOnDelete();

            $table->string('device_category', 20)->index(); // 'ALAT' or 'TUBE'
            $table->string('device_name', 100); // e.g., "IV Line", "Urin Kateter"
            $table->string('ukuran', 20)->nullable();
            $table->string('lokasi', 50)->nullable();
            $table->date('tanggal_pasang')->nullable();
            $table->timestamps();
        });

        // Hapus kolom textarea lama dari tabel induk SEKARANG
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn(['alat_terpasang', 'tube_terpasang']);
        });
    }

    public function down(): void
    {
         // Buat kembali kolom textarea lama JIKA rollback
         Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->text('alat_terpasang')->nullable()->after('catatan_lain_lain');
            $table->text('tube_terpasang')->nullable()->after('alat_terpasang');
        });

        Schema::dropIfExists('monitoring_devices');
    }
};
