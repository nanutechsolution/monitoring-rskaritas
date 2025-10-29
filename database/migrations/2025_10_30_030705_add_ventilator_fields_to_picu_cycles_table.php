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
        Schema::table('picu_cycles', function (Blueprint $table) {
            // Field ini akan jadi penanda (SPONTAN, CPAP, HFO, BLLOD GAS MONITOR)
            $table->string('vent_mode', 50)->nullable()->after('stimulasi');

            // Kolom untuk SPONTAN / Nasal High/Low Flow
            $table->decimal('vent_fio2_nasal', 4, 1)->nullable()->after('vent_mode');

            $table->decimal('vent_flow_nasal', 4, 1)->nullable()->after('vent_fio2_nasal');

            // Kolom untuk CPAP
            $table->decimal('vent_fio2_cpap', 4, 1)->nullable()->after('vent_flow_nasal');
            $table->decimal('vent_flow_cpap', 4, 1)->nullable()->after('vent_fio2_cpap');
            $table->decimal('vent_peep_cpap', 4, 1)->nullable()->after('vent_flow_cpap');

            // Kolom untuk HFO
            $table->decimal('vent_fio2_hfo', 4, 1)->nullable()->after('vent_peep_cpap');
            $table->integer('vent_frekuensi_hfo')->nullable()->after('vent_fio2_hfo');
            $table->decimal('vent_map_hfo', 4, 1)->nullable()->after('vent_frekuensi_hfo');
            $table->integer('vent_amplitudo_hfo')->nullable()->after('vent_map_hfo');
            $table->string('vent_it_hfo', 10)->nullable()->after('vent_amplitudo_hfo');

            // Kolom untuk BLLOD GAS MONITOR (Ventilator Mekanik)
            $table->string('vent_mode_mekanik', 50)->nullable()->after('vent_it_hfo');
            $table->decimal('vent_fio2_mekanik', 4, 1)->nullable()->after('vent_mode_mekanik');
            $table->decimal('vent_peep_mekanik', 4, 1)->nullable()->after('vent_fio2_mekanik');
            $table->decimal('vent_pip_mekanik', 4, 1)->nullable()->after('vent_peep_mekanik');
            $table->string('vent_tv_vte_mekanik', 20)->nullable()->after('vent_pip_mekanik');
            $table->string('vent_rr_spontan_mekanik', 20)->nullable()->after('vent_tv_vte_mekanik');
            $table->decimal('vent_p_max_mekanik', 4, 1)->nullable()->after('vent_rr_spontan_mekanik');
            $table->string('vent_ie_mekanik', 10)->nullable()->after('vent_p_max_mekanik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('picu_cycles', function (Blueprint $table) {
            $columns = [
                'vent_mode',
                'vent_fio2_nasal',
                'vent_flow_nasal',
                'vent_fio2_cpap',
                'vent_flow_cpap',
                'vent_peep_cpap',
                'vent_fio2_hfo',
                'vent_frekuensi_hfo',
                'vent_map_hfo',
                'vent_amplitudo_hfo',
                'vent_it_hfo',
                'vent_mode_mekanik',
                'vent_fio2_mekanik',
                'vent_peep_mekanik',
                'vent_pip_mekanik',
                'vent_tv_vte_mekanik',
                'vent_rr_spontan_mekanik',
                'vent_p_max_mekanik',
                'vent_ie_mekanik'
            ];
            $table->dropColumn($columns);
        });
    }
};
