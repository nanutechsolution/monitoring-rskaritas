<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegPeriksa extends Model
{
    use HasFactory;

    /**
     * Tentukan koneksi database (jika database Khanza terpisah).
     * Hapus jika sudah satu database.
     * * @var string
     */
    // protected $connection = 'khanza';

    /**
     * Nama tabel yang terhubung dengan model.
     * @var string
     */
    protected $table = 'reg_periksa';

    /**
     * Primary key untuk model ini.
     * @var string
     */
    protected $primaryKey = 'no_rawat';

    /**
     * Tipe data dari primary key.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Tentukan apakah primary key auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tentukan apakah model punya timestamps (created_at & updated_at).
     * @var bool
     */
    public $timestamps = false;


    /**
     * Relasi many-to-one: Satu registrasi periksa milik SATU poliklinik.
     */
    public function poliklinik(): BelongsTo
    {
        // Foreign key di tabel ini ('reg_periksa') adalah 'kd_poli'
        // Primary key di tabel 'poliklinik' adalah 'kd_poli'
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    /**
     * Relasi one-to-many: Satu registrasi bisa punya BANYAK lembar cycle ICU.
     */
    public function monitoringCyclesIcu(): HasMany
    {
        return $this->hasMany(MonitoringCycleIcu::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Relasi ke data pasien.
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    /**
     * Relasi one-to-many: Satu registrasi bisa punya BANYAK record kamar inap (riwayat).
     */
    public function kamarInap(): HasMany // <-- Tambahkan relasi ini jika perlu
    {
        // Foreign key di 'kamar_inap' adalah 'no_rawat'
        return $this->hasMany(KamarInap::class, 'no_rawat', 'no_rawat');
    }


    /**
     * Relasi many-to-one: Satu registrasi periksa milik SATU Penanggung Jawab (Penjab).
     */
    public function penjab(): BelongsTo
    {
        // Foreign key di tabel ini ('reg_periksa') adalah 'kd_pj'
        // Primary key di tabel 'penjab' adalah 'kd_pj'
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }


}
