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
        Schema::table('therapy_programs', function (Blueprint $table) {
            // 1. Tambahkan 5 kolom baru
            $table->text('masalah_klinis')->after('id_user');
            $table->text('program_terapi')->after('masalah_klinis');
            $table->text('nutrisi_enteral')->after('program_terapi');
            $table->text('nutrisi_parenteral')->after('nutrisi_enteral');
            $table->text('pemeriksaan_lab')->after('nutrisi_parenteral');

            // 2. Hapus kolom lama
            $table->dropColumn('program_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapy_programs', function (Blueprint $table) {
            // 1. Kembalikan kolom lama
            $table->text('program_text')->after('id_user');

            // 2. Hapus 5 kolom baru
            $table->dropColumn([
                'masalah_klinis',
                'program_terapi',
                'nutrisi_enteral',
                'nutrisi_parenteral',
                'pemeriksaan_lab'
            ]);
        });
    }
};
