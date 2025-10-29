<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            // Tambahkan kolom setelah 'end_time' misalnya
            $table->text('ventilator_notes')->nullable()->after('end_time');
        });
    }
    public function down(): void {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn('ventilator_notes');
        });
    }
};
