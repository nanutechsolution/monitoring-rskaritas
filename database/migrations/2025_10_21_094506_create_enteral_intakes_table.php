<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enteral_intakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_record_id')
                ->constrained('monitoring_records')
                ->onDelete('cascade');
            $table->string('name'); // contoh: Susu, ASI, Puasa
            $table->decimal('volume', 8, 2)->nullable(); // boleh null kalau puasa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enteral_intakes');
    }
};
