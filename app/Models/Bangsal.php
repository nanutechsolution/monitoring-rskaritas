<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bangsal extends Model
{
    // Tidak pakai HasFactory

    /**
     * Nama tabel.
     * @var string
     */
    protected $table = 'bangsal';

    /**
     * Primary key.
     * @var string
     */
    protected $primaryKey = 'kd_bangsal';

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

    // --- DEFINISI RELASI ---

    /**
     * Relasi one-to-many: Satu bangsal memiliki BANYAK kamar.
     */
    public function kamar(): HasMany
    {
        // Foreign key di tabel 'kamar' adalah 'kd_bangsal'
        // Primary key di tabel ini ('bangsal') adalah 'kd_bangsal'
        return $this->hasMany(Kamar::class, 'kd_bangsal', 'kd_bangsal');
    }
}
