<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            // Kolom untuk menyimpan hasil kalkulasi akhir BC 24 jam
            $table->decimal('calculated_balance_24h', 8, 2)->nullable()->after('daily_iwl');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->dropColumn('calculated_balance_24h');
        });
    }
};
