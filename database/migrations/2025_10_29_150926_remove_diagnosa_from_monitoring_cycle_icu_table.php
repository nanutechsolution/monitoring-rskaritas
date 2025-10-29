<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn('diagnosa');
        });
    }
    public function down(): void {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->text('diagnosa')->nullable()->after('end_time'); // Sesuaikan 'after'
        });
    }
};
