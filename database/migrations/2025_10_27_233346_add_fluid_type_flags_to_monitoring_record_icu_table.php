<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->boolean('is_enteral')->nullable()->default(false)->after('cairan_masuk_volume');
            $table->boolean('is_parenteral')->nullable()->default(false)->after('is_enteral');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn(['is_enteral', 'is_parenteral']);
        });
    }
};
