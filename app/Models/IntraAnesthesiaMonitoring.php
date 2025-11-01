<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntraAnesthesiaMonitoring extends Model
{
    use HasFactory;
    protected $casts = [
        'tanggal_lahir' => 'date',
        'mulai_anestesia' => 'datetime',
        'selesai_anestesia' => 'datetime',
        'mulai_pembedahan' => 'datetime',
        'selesai_pembedahan' => 'datetime',

        // Ubah 0/1 menjadi true/false
        'perlindungan_mata' => 'boolean',
        'jalan_nafas_oro_nasopharing' => 'boolean',
        'jalan_nafas_trakheostomi' => 'boolean',
        'jalan_nafas_bronkoskopi_fiberoptik' => 'boolean',
        'jalan_nafas_glidescope' => 'boolean',
        'sulit_ventilasi' => 'boolean',
        'sulit_intubasi' => 'boolean',
        'intubasi_dengan_stilet' => 'boolean',
        'intubasi_cuff' => 'boolean',
        'intubasi_pack' => 'boolean',
        'regional_kateter' => 'boolean',
    ];

    /**
     * Daftar kolom yang boleh diisi secara massal (mass assignable).
     * * Ini harus berisi SETIAP kolom yang Anda simpan di fungsi ::create()
     */
    protected $fillable = [
        'no_rawat',
        'no_rekam_medis',
        'nama_lengkap',
        'tanggal_lahir',
        'no_ktp_paspor',
        'kd_dokter_anestesi',
        'nip_penata_anestesi',
        'infus_perifer_1_tempat_ukuran',
        'infus_perifer_2_tempat_ukuran',
        'cvc',
        'posisi',
        'perlindungan_mata',
        'premedikasi_oral',
        'premedikasi_iv',
        'induksi_intravena',
        'induksi_inhalasi',
        'jalan_nafas_facemask_no',
        'jalan_nafas_oro_nasopharing',
        'jalan_nafas_ett_no',
        'jalan_nafas_ett_jenis',
        'jalan_nafas_ett_fiksasi_cm',
        'jalan_nafas_lma_no',
        'jalan_nafas_lma_jenis',
        'jalan_nafas_trakheostomi',
        'jalan_nafas_bronkoskopi_fiberoptik',
        'jalan_nafas_glidescope',
        'jalan_nafas_lain_lain',
        'intubasi_kondisi',
        'intubasi_jalan',
        'sulit_ventilasi',
        'sulit_intubasi',
        'intubasi_dengan_stilet',
        'intubasi_cuff',
        'intubasi_level_ett',
        'intubasi_pack',
        'ventilasi_mode',
        'ventilator_tv',
        'ventilator_rr',
        'ventilator_peep',
        'konversi',
        'mulai_anestesia',
        'selesai_anestesia',
        'mulai_pembedahan',
        'selesai_pembedahan',
        'lama_pembiusan',
        'lama_pembedahan',
        'regional_jenis',
        'regional_lokasi',
        'regional_jenis_jarum_no',
        'regional_kateter',
        'regional_fiksasi_cm',
        'regional_obat_obat',
        'regional_komplikasi',
        'regional_hasil',
        'masalah_intra_anestesi',
        'total_cairan_infus_ml',
        'total_darah_ml',
        'total_urin_ml',
        'total_perdarahan_ml',
    ];


    // --- Relasi Anda yang lain ada di bawah sini ---

    public function registrasi()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    public function dokterAnestesi()
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter_anestesi', 'kd_dokter');
    }

    public function penataAnestesi()
    {
        return $this->belongsTo(Petugas::class, 'nip_penata_anestesi', 'nip');
    }

    public function vitals()
    {
        return $this->hasMany(MonitoringVital::class);
    }

    public function medications()
    {
        return $this->hasMany(MonitoringMedication::class);
    }
}
