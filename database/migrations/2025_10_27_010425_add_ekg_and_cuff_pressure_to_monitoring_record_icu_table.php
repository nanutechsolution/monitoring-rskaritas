<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Tambahkan 2 kolom baru ini
            $table->string('irama_ekg', 50)->nullable()->after('map');
            $table->decimal('cuff_pressure', 4, 1)->nullable()->after('cvp');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn(['irama_ekg', 'cuff_pressure']);
        });
    }
};
