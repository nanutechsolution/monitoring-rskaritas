<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parenteral_intakes', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke 'monitoring_records' bukan 'monitoring_cycles'
            $table->foreignId('monitoring_record_id')->constrained('monitoring_records')->cascadeOnDelete();
            $table->string('name'); // Nama cairan infus, cth: D10%
            $table->decimal('volume', 8, 2); // Jumlah (cc)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parenteral_intakes');
    }
};
