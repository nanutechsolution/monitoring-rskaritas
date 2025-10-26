<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dokter extends Model
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
    protected $table = 'dokter';

    /**
     * Primary key untuk model ini.
     * @var string
     */
    protected $primaryKey = 'kd_dokter';

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

    // --- DEFINISI RELASI ---

    /**
     * Relasi many-to-many: Satu dokter bisa menjadi DPJP di BANYAK cycle ICU.
     */
    public function monitoringCyclesIcu(): BelongsToMany
    {
        return $this->belongsToMany(
            MonitoringCycleIcu::class,
            'dpjp_monitoring_cycle_icu', // Nama tabel pivot
            'kd_dokter',                   // Foreign key di pivot untuk Dokter
            'monitoring_cycle_icu_id'    // Foreign key di pivot untuk Cycle
        );
    }
}
