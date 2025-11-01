<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
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
    protected $table = 'pasien';

    /**
     * Primary key untuk model ini.
     * @var string
     */
    protected $primaryKey = 'no_rkm_medis';

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
    protected $casts = [
        'tgl_lahir' => 'date',
    ];
    // --- DEFINISI RELASI ---

    /**
     * Relasi one-to-many: Satu pasien bisa punya BANYAK data registrasi.
     */
    public function registrasi(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'no_rkm_medis', 'no_rkm_medis');
    }
}
