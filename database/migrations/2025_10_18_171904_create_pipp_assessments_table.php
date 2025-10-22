<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pipp_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_cycle_id')->constrained('monitoring_cycles')->cascadeOnDelete();
            $table->string('id_user', 700);
            $table->dateTime('assessment_time');

            // PIPP Indicators (Skor 0-3)
            $table->unsignedTinyInteger('gestational_age')->default(0); // Usia Gestasi
            $table->unsignedTinyInteger('behavioral_state')->default(0); // Perilaku Bayi
            $table->unsignedTinyInteger('max_heart_rate')->default(0); // Laju Nadi Maks
            $table->unsignedTinyInteger('min_oxygen_saturation')->default(0); // Saturasi Oksigen Min
            $table->unsignedTinyInteger('brow_bulge')->default(0); // Tarikan Alis
            $table->unsignedTinyInteger('eye_squeeze')->default(0); // Kerutan Mata
            $table->unsignedTinyInteger('nasolabial_furrow')->default(0); // Alur Nasolabial

            $table->unsignedTinyInteger('total_score'); // Total Skor (Calculated)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipp_assessments');
    }
};
