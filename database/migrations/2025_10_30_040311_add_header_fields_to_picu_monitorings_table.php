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
            $table->string('umur_kehamilan', 20)->nullable()->after('diagnosis');
            $table->string('umur_koreksi', 20)->nullable()->after('umur_kehamilan');
            $table->string('berat_badan_lahir', 20)->nullable()->after('umur_koreksi');
            $table->string('cara_persalinan', 50)->nullable()->after('berat_badan_lahir');
            $table->string('rujukan', 100)->nullable()->after('cara_persalinan');
            $table->string('asal_ruangan', 100)->nullable()->after('rujukan');
            $table->string('jaminan', 100)->nullable()->after('asal_ruangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('picu_monitorings', function (Blueprint $table) {
            $table->dropColumn([
                'umur_kehamilan',
                'umur_koreksi',
                'berat_badan_lahir',
                'cara_persalinan',
                'rujukan',
                'asal_ruangan',
                'jaminan',
            ]);
        });
    }
};
