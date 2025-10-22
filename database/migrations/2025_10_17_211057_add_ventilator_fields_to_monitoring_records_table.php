<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->string('respiratory_mode')->nullable()->after('humidifier_inkubator');

            // Spontan Fields
            $table->string('spontan_fio2')->nullable();
            $table->string('spontan_flow')->nullable();

            // CPAP Fields
            $table->string('cpap_fio2')->nullable();
            $table->string('cpap_flow')->nullable();
            $table->string('cpap_peep')->nullable();

            // HFO Fields
            $table->string('hfo_fio2')->nullable();
            $table->string('hfo_frekuensi')->nullable();
            $table->string('hfo_map')->nullable();
            $table->string('hfo_amplitudo')->nullable();
            $table->string('hfo_it')->nullable();

            // Monitor Fields
            $table->string('monitor_mode')->nullable();
            $table->string('monitor_fio2')->nullable();
            $table->string('monitor_peep')->nullable();
            $table->string('monitor_pip')->nullable();
            $table->string('monitor_tv_vte')->nullable();
            $table->string('monitor_rr_spontan')->nullable();
            $table->string('monitor_p_max')->nullable();
            $table->string('monitor_ie')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn([
                'respiratory_mode',
                'spontan_fio2', 'spontan_flow',
                'cpap_fio2', 'cpap_flow', 'cpap_peep',
                'hfo_fio2', 'hfo_frekuensi', 'hfo_map', 'hfo_amplitudo', 'hfo_it',
                'monitor_mode', 'monitor_fio2', 'monitor_peep', 'monitor_pip',
                'monitor_tv_vte', 'monitor_rr_spontan', 'monitor_p_max', 'monitor_ie',
            ]);
        });
    }
};
