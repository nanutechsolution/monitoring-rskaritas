<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DpjpRanap extends Model
{
    protected $table = 'dpjp_ranap';
    // Tabel ini punya composite primary key, kita tidak set $primaryKey
    public $incrementing = false;
    public $timestamps = false;

    /** Relasi ke Dokter */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    /** Relasi ke RegPeriksa */
    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
