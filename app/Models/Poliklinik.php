<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poliklinik extends Model
{
    // Tidak pakai HasFactory

    /**
     * Nama tabel.
     * @var string
     */
    protected $table = 'poliklinik';

    /**
     * Primary key (berdasarkan foreign key di reg_periksa).
     * @var string
     */
    protected $primaryKey = 'kd_poli';

    /**
     * Tipe data primary key (biasanya string untuk kode).
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Primary key TIDAK auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tabel ini TIDAK punya timestamps.
     * @var bool
     */
    public $timestamps = false;

    // --- DEFINISI RELASI ---

    /**
     * Relasi one-to-many: Satu poliklinik bisa punya BANYAK registrasi periksa.
     */
    public function regPeriksa(): HasMany
    {
        // Foreign key di tabel 'reg_periksa' adalah 'kd_poli'
        // Primary key di tabel ini ('poliklinik') adalah 'kd_poli'
        return $this->hasMany(RegPeriksa::class, 'kd_poli', 'kd_poli');
    }
}
