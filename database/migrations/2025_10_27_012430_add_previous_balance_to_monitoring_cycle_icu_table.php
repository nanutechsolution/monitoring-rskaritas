<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            // Kolom untuk menyimpan total balance dari HARI SEBELUMNYA
            $table->decimal('previous_balance', 10, 2)
                  ->nullable()
                  ->default(0)
                  ->after('calculated_balance_24h'); // Letakkan setelah BC 24 Jam
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_cycle_icu', function (Blueprint $table) {
            $table->dropColumn('previous_balance');
        });
    }
};
