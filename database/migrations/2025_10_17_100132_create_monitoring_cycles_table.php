<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitoring_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 17);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();

            $table->index('no_rawat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_cycles');
    }
};
