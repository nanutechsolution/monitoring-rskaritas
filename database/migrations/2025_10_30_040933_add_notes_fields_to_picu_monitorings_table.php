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
        Schema::table('picu_monitorings', function (Blueprint $table) {
            $table->text('masalah')->nullable()->after('jaminan');
            $table->text('program_terapi')->nullable()->after('masalah');
            $table->text('catatan_nutrisi')->nullable()->after('program_terapi');
            $table->text('catatan_lab')->nullable()->after('catatan_nutrisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('picu_monitorings', function (Blueprint $table) {
            $table->dropColumn([
                'masalah',
                'program_terapi',
                'catatan_nutrisi',
                'catatan_lab',
            ]);
        });
    }
};
