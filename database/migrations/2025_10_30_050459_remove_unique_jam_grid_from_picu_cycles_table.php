<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN INI

return new class extends Migration
{
    public function up(): void
    {
        // MATIKAN foreign key checks sebelum aksi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::table('picu_cycles', function (Blueprint $table) {
            // Hapus constraint yang mencegah multiple input per jam
            $table->dropUnique(['picu_monitoring_id', 'jam_grid']);
        });

        // NYALAKAN KEMBALI foreign key checks setelah selesai
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // Juga tambahkan di down() untuk keamanan rollback
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('picu_cycles', function (Blueprint $table) {
            // Kembalikan constraint unik
            $table->unique(['picu_monitoring_id', 'jam_grid']);
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
