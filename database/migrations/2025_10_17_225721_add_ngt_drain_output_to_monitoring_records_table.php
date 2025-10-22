<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->decimal('output_ngt', 8, 2)->nullable()->after('output_residu');
            $table->decimal('output_drain', 8, 2)->nullable()->after('output_ngt');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
            $table->dropColumn(['output_ngt', 'output_drain']);
        });
    }
};
