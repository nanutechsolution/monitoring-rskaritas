<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    // Tidak pakai HasFactory

    /**
     * Nama tabel.
     * @var string
     */
    protected $table = 'kamar';

    /**
     * Primary key.
     * @var string
     */
    protected $primaryKey = 'kd_kamar';

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
     * Relasi many-to-one: Satu kamar milik SATU bangsal.
     */
    public function bangsal(): BelongsTo
    {
        // Foreign key di tabel ini ('kamar') adalah 'kd_bangsal'
        // Primary key di tabel 'bangsal' adalah 'kd_bangsal'
        return $this->belongsTo(Bangsal::class, 'kd_bangsal', 'kd_bangsal');
    }

    /**
     * Relasi one-to-many: Satu kamar bisa ditempati BANYAK data kamar inap (riwayat).
     */
    public function kamarInap(): HasMany
    {
        // Foreign key di tabel 'kamar_inap' adalah 'kd_kamar'
        // Primary key di tabel ini ('kamar') adalah 'kd_kamar'
        return $this->hasMany(KamarInap::class, 'kd_kamar', 'kd_kamar');
    }
}
