<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('picu_blood_gas_logs', function (Blueprint $table) {
            // Ubah kolom 'ph' agar lebih besar
            $table->decimal('ph', 4, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('picu_blood_gas_logs', function (Blueprint $table) {
            // Kembalikan ke definisi lama jika di-rollback
            $table->decimal('ph', 3, 2)->nullable()->change();
        });
    }
};
