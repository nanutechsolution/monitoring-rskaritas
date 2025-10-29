<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Tambahkan kolom setelah GCS atau Pupil
            $table->string('fall_risk_assessment', 50)->nullable()->after('pupil_right_reflex');
        });
    }
    public function down(): void {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn('fall_risk_assessment');
        });
    }
};
