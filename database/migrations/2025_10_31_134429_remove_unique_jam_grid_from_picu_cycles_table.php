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
        // Schema::table('picu_cycles', function (Blueprint $table) {
        //     // 1. Hapus Foreign Key dulu
        //     $table->dropForeign(['picu_monitoring_id']);

        //     // 2. Hapus Unique Index (Sekarang aman karena FK sudah dilepas)
        //     $table->dropUnique(['picu_monitoring_id', 'jam_grid']);

        //     // 3. Pasang lagi Foreign Key (tanpa unique index)
        //     $table->foreign('picu_monitoring_id')
        //         ->references('id')
        //         ->on('picu_monitorings')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('picu_cycles', function (Blueprint $table) {
        //     // Balikkan prosesnya jika di-rollback
        //     $table->dropForeign(['picu_monitoring_id']);
        //     $table->unique(['picu_monitoring_id', 'jam_grid']);
        //     $table->foreign('picu_monitoring_id')
        //         ->references('id')
        //         ->on('picu_monitorings')
        //         ->onDelete('cascade');
        // });
    }
};
