<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            // Tambahkan setelah PEEP
            $table->unsignedSmallInteger('ventilator_pinsp')->nullable()->after('ventilator_peep');
            $table->string('ventilator_ie_ratio', 10)->nullable()->after('ventilator_pinsp'); // cth: '1:2', '1:1.5'
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_record_icu', function (Blueprint $table) {
            $table->dropColumn(['ventilator_pinsp', 'ventilator_ie_ratio']);
        });
    }
};
