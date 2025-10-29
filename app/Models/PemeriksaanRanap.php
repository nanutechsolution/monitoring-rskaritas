<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemeriksaanRanap extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'pemeriksaan_ranap';

    /**
     * Beri tahu Eloquent bahwa tabel ini tidak punya
     * kolom created_at dan updated_at
     */
    public $timestamps = false;

    /**
     * Beri tahu Eloquent bahwa tabel ini tidak punya
     * primary key 'id' auto-increment.
     */

    protected $primaryKey = ['no_rawat', 'tgl_perawatan', 'jam_rawat'];
    public $incrementing = false;

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'no_rawat',
        'tgl_perawatan',
        'jam_rawat',
        'suhu_tubuh',
        'tensi',
        'nadi',
        'respirasi',
        'tinggi',
        'berat',
        'spo2',
        'gcs',
        'kesadaran',
        'keluhan',
        'pemeriksaan',
        'alergi',
        'penilaian',
        'rtl',
        'instruksi',
        'evaluasi',
        'nip',
    ];

    /**
     * Cast tanggal dan jam agar mudah dikelola
     */
    protected $casts = [
        'tgl_perawatan' => 'date',
    ];

    /**
     * Relasi ke Pegawai
     * 'nip' (di tabel ini) terhubung ke 'nik' (di tabel pegawai)
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nik');
    }

    /**
     * Helper untuk menggabungkan tgl & jam
     */
    public function getWaktuPemeriksaanAttribute()
    {
        return \Carbon\Carbon::parse($this->tgl_perawatan->format('Y-m-d') . ' ' . $this->jam_rawat);
    }

    /**
     * Relasi ke reg_periksa (jika diperlukan)
     */
    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
