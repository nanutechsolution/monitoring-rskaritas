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
        Schema::create('intra_anesthesia_monitorings', function (Blueprint $table) {
            $table->id(); // Primary key untuk tabel INI

            // == RELASI & IDENTITAS PASIEN (DISESUAIKAN DENGAN SKEMA KHANZA) ==
            $table->string('no_rawat', 17)->index();
            $table->string('no_rekam_medis', 15)->nullable()->index();
            $table->string('nama_lengkap', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_ktp_paspor', 20)->nullable();

            // Relasi ke staf
            $table->string('kd_dokter_anestesi', 20)->nullable()->index();
            $table->string('nip_penata_anestesi', 20)->nullable()->index();

            // == PERSIAPAN ==
            $table->string('infus_perifer_1_tempat_ukuran')->nullable(); //
            $table->string('infus_perifer_2_tempat_ukuran')->nullable(); //
            $table->string('cvc')->nullable(); //
            $table->string('posisi')->nullable(); //
            $table->boolean('perlindungan_mata')->default(false); //

            // == PREMEDIKASI & INDUKSI ==
            $table->string('premedikasi_oral')->nullable(); //
            $table->string('premedikasi_iv')->nullable();
            $table->string('induksi_intravena')->nullable(); //
            $table->string('induksi_inhalasi')->nullable(); //

            // == TATA LAKSANA JALAN NAFAS ==
            $table->string('jalan_nafas_facemask_no')->nullable(); //
            $table->boolean('jalan_nafas_oro_nasopharing')->default(false); //
            $table->string('jalan_nafas_ett_no')->nullable(); //
            $table->string('jalan_nafas_ett_jenis')->nullable(); //
            $table->string('jalan_nafas_ett_fiksasi_cm')->nullable(); //
            $table->string('jalan_nafas_lma_no')->nullable(); //
            $table->string('jalan_nafas_lma_jenis')->nullable(); //
            $table->boolean('jalan_nafas_trakheostomi')->default(false); //
            $table->boolean('jalan_nafas_bronkoskopi_fiberoptik')->default(false); //
            $table->boolean('jalan_nafas_glidescope')->default(false); //
            $table->string('jalan_nafas_lain_lain')->nullable(); //

            // == INTUBASI ==
            $table->string('intubasi_kondisi')->nullable(); //
            $table->string('intubasi_jalan')->nullable(); //
            $table->boolean('sulit_ventilasi')->default(false); //
            $table->boolean('sulit_intubasi')->default(false); //
            $table->boolean('intubasi_dengan_stilet')->default(false); //
            $table->boolean('intubasi_cuff')->default(false); //
            $table->string('intubasi_level_ett')->nullable(); //
            $table->boolean('intubasi_pack')->default(false); //

            // == VENTILASI ==
            $table->string('ventilasi_mode')->nullable(); //
            $table->string('ventilator_tv')->nullable(); //
            $table->string('ventilator_rr')->nullable(); //
            $table->string('ventilator_peep')->nullable(); //
            $table->string('konversi')->nullable(); //

            // == WAKTU ==
            $table->timestamp('mulai_anestesia')->nullable(); //
            $table->timestamp('selesai_anestesia')->nullable(); //
            $table->timestamp('mulai_pembedahan')->nullable(); //
            $table->timestamp('selesai_pembedahan')->nullable(); //
            $table->string('lama_pembiusan')->nullable(); //
            $table->string('lama_pembedahan')->nullable(); //

            // == TEKNIK REGIONAL / BLOK PERIFER ==
            $table->string('regional_jenis')->nullable(); //
            $table->string('regional_lokasi')->nullable(); //
            $table->string('regional_jenis_jarum_no')->nullable(); //
            $table->boolean('regional_kateter')->default(false); //
            $table->string('regional_fiksasi_cm')->nullable(); //
            $table->text('regional_obat_obat')->nullable(); //
            $table->text('regional_komplikasi')->nullable(); //
            $table->string('regional_hasil')->nullable(); //

            // == CATATAN AKHIR & TOTAL ==
            $table->text('masalah_intra_anestesi')->nullable(); //
            $table->integer('total_cairan_infus_ml')->nullable(); //
            $table->integer('total_darah_ml')->nullable(); //
            $table->integer('total_urin_ml')->nullable(); //
            $table->integer('total_perdarahan_ml')->nullable(); //

            $table->timestamps(); // kapan record ini dibuat dan diupdate

            // == DEFINISI FOREIGN KEY ==
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('cascade');
            $table->foreign('kd_dokter_anestesi')->references('kd_dokter')->on('dokter')->onDelete('set null');
            $table->foreign('nip_penata_anestesi')->references('nip')->on('petugas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intra_anesthesia_monitorings');
    }
};
