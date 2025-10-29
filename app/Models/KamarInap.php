<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KamarInap extends Model
{
    // Tidak pakai HasFactory karena tabel sudah ada

    /**
     * Nama tabel.
     * @var string
     */
    protected $table = 'kamar_inap';

    /**
     * Primary key BUKAN 'id'. Tabel ini punya composite primary key,
     * tapi Eloquent tidak support composite PK secara native.
     * Kita set 'no_rawat' sebagai acuan, tapi JANGAN anggap ini PK unik.
     * @var string
     */
    protected $primaryKey = 'no_rawat'; // Anggap ini sebagai acuan utama

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
     * Tabel ini TIDAK punya timestamps (created_at/updated_at).
     * @var bool
     */
    public $timestamps = false;

    /**
     * Tipe data kolom tanggal dan waktu.
     */
    protected $casts = [
        'tgl_masuk' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'tgl_keluar' => 'date',
        'jam_keluar' => 'datetime:H:i:s',
    ];

    // --- DEFINISI RELASI ---

    /**
     * Relasi ke tabel 'kamar'.
     */
    public function kamar(): BelongsTo
    {
        // Foreign key 'kd_kamar', Primary key di tabel 'kamar' adalah 'kd_kamar'
        return $this->belongsTo(Kamar::class, 'kd_kamar', 'kd_kamar');
    }

    /**
     * Relasi ke tabel 'reg_periksa'.
     */
    public function regPeriksa(): BelongsTo
    {
        // Foreign key 'no_rawat', Primary key di tabel 'reg_periksa' adalah 'no_rawat'
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
