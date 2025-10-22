<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->text('therapy_program')->nullable()->after('no_rawat');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_cycles', function (Blueprint $table) {
            $table->dropColumn('therapy_program');
        });
    }
};
