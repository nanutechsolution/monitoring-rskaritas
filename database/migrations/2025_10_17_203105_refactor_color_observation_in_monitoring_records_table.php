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
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->boolean('cyanosis')->default(false);
            $table->boolean('pucat')->default(false);
            $table->boolean('ikterus')->default(false);
            $table->boolean('crt_less_than_2')->default(false);
            $table->boolean('bradikardia')->default(false);
            $table->boolean('stimulasi')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn(['cyanosis', 'pucat', 'ikterus', 'crt_less_than_2', 'bradikardia', 'stimulasi']);
        });
    }
};
