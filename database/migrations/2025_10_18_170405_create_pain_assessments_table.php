<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pain_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_cycle_id')->constrained('monitoring_cycles')->cascadeOnDelete();
            $table->string('id_user', 700);
            $table->dateTime('assessment_time'); // Waktu penilaian

            // NIPS Indicators (Skor 0 atau 1, kecuali menangis 0/1/2)
            $table->unsignedTinyInteger('facial_expression')->default(0); // Ekspresi Muka
            $table->unsignedTinyInteger('cry')->default(0);             // Menangis
            $table->unsignedTinyInteger('breathing_pattern')->default(0); // Pola Nafas
            $table->unsignedTinyInteger('arms_movement')->default(0);     // Gerakan Lengan
            $table->unsignedTinyInteger('legs_movement')->default(0);     // Gerakan Kaki
            $table->unsignedTinyInteger('state_of_arousal')->default(0); // Tingkat Kesadaran

            $table->unsignedTinyInteger('total_score'); // Total Skor (Calculated)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pain_assessments');
    }
};
