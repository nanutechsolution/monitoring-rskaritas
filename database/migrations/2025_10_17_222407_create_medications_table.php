<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_cycle_id')->constrained('monitoring_cycles')->cascadeOnDelete();
            $table->string('id_user', 700);
            $table->string('medication_name');
            $table->string('dose');
            $table->string('route'); // Rute: IV, Oral, dll.
            $table->dateTime('given_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
