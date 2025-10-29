<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            // Tambahkan kolom target nutrisi (setelah 'catatan_lain_lain' misalnya)
            $table->decimal('enteral_target_volume', 8, 2)->nullable()->after('catatan_lain_lain');
            $table->integer('enteral_target_kalori')->nullable()->after('enteral_target_volume');
            $table->integer('enteral_target_protein')->nullable()->after('enteral_target_kalori');
            $table->integer('enteral_target_lemak')->nullable()->after('enteral_target_protein');

            $table->decimal('parenteral_target_volume', 8, 2)->nullable()->after('enteral_target_lemak');
            $table->integer('parenteral_target_kalori')->nullable()->after('parenteral_target_volume');
            $table->integer('parenteral_target_protein')->nullable()->after('parenteral_target_kalori');
            $table->integer('parenteral_target_lemak')->nullable()->after('parenteral_target_protein');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn([
                'enteral_target_volume', 'enteral_target_kalori', 'enteral_target_protein', 'enteral_target_lemak',
                'parenteral_target_volume', 'parenteral_target_kalori', 'parenteral_target_protein', 'parenteral_target_lemak',
            ]);
        });
    }
};
