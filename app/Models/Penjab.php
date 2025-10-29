<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjab extends Model
{
    // Tidak pakai HasFactory

    /**
     * Nama tabel.
     * @var string
     */
    protected $table = 'penjab';

    /**
     * Primary key.
     * @var string
     */
    protected $primaryKey = 'kd_pj';

    /**
     * Tipe data primary key.
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

    /**
     * Relasi one-to-many: Satu penjab bisa digunakan oleh BANYAK registrasi periksa.
     */
    public function regPeriksa(): HasMany
    {
        // Foreign key di tabel 'reg_periksa' adalah 'kd_pj'
        // Primary key di tabel ini ('penjab') adalah 'kd_pj'
        return $this->hasMany(RegPeriksa::class, 'kd_pj', 'kd_pj');
    }
}
