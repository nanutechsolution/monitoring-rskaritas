<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Tambahkan kolom setelah GCS
            $table->unsignedTinyInteger('pupil_left_size_mm')->nullable()->after('gcs_total');
            $table->string('pupil_left_reflex', 5)->nullable()->after('pupil_left_size_mm'); // Misal: '+', '-'
            $table->unsignedTinyInteger('pupil_right_size_mm')->nullable()->after('pupil_left_reflex');
            $table->string('pupil_right_reflex', 5)->nullable()->after('pupil_right_size_mm');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn([
                'pupil_left_size_mm',
                'pupil_left_reflex',
                'pupil_right_size_mm',
                'pupil_right_reflex'
            ]);
        });
    }
};
