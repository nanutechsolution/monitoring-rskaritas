<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_gas_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_cycle_id')->constrained('monitoring_cycles')->cascadeOnDelete();
            $table->string('id_user', 700);
            $table->dateTime('taken_at'); // Waktu pengambilan sampel

            // Parameter Hasil Tes
            $table->decimal('gula_darah', 8, 2)->nullable();
            $table->decimal('ph', 8, 2)->nullable();
            $table->decimal('pco2', 8, 2)->nullable();
            $table->decimal('po2', 8, 2)->nullable();
            $table->decimal('hco3', 8, 2)->nullable();
            $table->decimal('be', 8, 2)->nullable();
            $table->decimal('sao2', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_gas_results');
    }
};
