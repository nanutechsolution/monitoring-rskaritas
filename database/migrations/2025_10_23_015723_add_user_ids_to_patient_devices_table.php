<?php
// Di dalam database/migrations/....add_user_ids_to_patient_devices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patient_devices', function (Blueprint $table) {
            // Kita tetap buat kolomnya sebagai string (VARCHAR)
            $table->string('installed_by_user_id', 20)->nullable()->after('installation_date');
            $table->string('removed_by_user_id', 20)->nullable()->after('removal_date');
        });
    }

    public function down(): void
    {
        Schema::table('patient_devices', function (Blueprint $table) {
            // Hapus kolomnya saja
            $table->dropColumn(['installed_by_user_id', 'removed_by_user_id']);
        });
    }
};
