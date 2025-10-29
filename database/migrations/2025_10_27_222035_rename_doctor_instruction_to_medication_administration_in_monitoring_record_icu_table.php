<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Rename kolom
            $table->renameColumn('doctor_instruction', 'medication_administration');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Rename kembali jika rollback
            $table->renameColumn('medication_administration', 'doctor_instruction');
        });
    }
};
