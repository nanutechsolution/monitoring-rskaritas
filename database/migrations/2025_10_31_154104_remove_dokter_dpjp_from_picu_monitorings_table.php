<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('picu_monitorings', function (Blueprint $table) {
            // Hapus kolom 'dokter_dpjp' yang lama
            $table->dropColumn('dokter_dpjp');
        });
    }

    public function down(): void
    {
        Schema::table('picu_monitorings', function (Blueprint $table) {
            // Jika di-rollback, kembalikan kolomnya
            $table->char('dokter_dpjp', 20)->nullable()->after('end_datetime');
        });
    }
};