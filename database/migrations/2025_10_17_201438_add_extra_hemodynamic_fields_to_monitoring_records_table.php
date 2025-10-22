<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->string('irama_ekg')->nullable()->after('sat_o2');
            $table->string('skala_nyeri')->nullable()->after('irama_ekg');
            $table->string('humidifier_inkubator')->nullable()->after('skala_nyeri');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn(['irama_ekg', 'skala_nyeri', 'humidifier_inkubator']);
        });
    }
};
