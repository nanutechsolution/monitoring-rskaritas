<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            // Intake (Cairan Masuk) - One-to-one
            $table->decimal('intake_ogt', 8, 2)->nullable()->after('respiratory_mode');
            $table->decimal('intake_oral', 8, 2)->nullable()->after('intake_ogt');

            // Output (Cairan Keluar)
            $table->decimal('output_urine', 8, 2)->nullable()->after('intake_oral');
            $table->decimal('output_bab', 8, 2)->nullable()->after('output_urine');
            $table->decimal('output_residu', 8, 2)->nullable()->after('output_bab'); // Untuk Residu/Muntah
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn([
                'intake_ogt', 'intake_oral',
                'output_urine', 'output_bab', 'output_residu'
            ]);
        });
    }
};
