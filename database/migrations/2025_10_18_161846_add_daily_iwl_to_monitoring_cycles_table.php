<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->decimal('daily_iwl', 8, 2)->nullable()->after('clinical_problems');
        });
    }
    public function down(): void
    {
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->dropColumn('daily_iwl');
        });
    }
};
