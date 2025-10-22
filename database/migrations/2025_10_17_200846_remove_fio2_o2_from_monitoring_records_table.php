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
            $table->dropColumn('fio2_o2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_records', function (Blueprint $table) {
           $table->string('fio2_o2')->nullable()->after('sat_o2');
        });
    }
};
