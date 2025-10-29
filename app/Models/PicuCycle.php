<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PicuCycle extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_observasi' => 'datetime',
        'cyanosis' => 'boolean',
        'pucat' => 'boolean',
        'icterus' => 'boolean',
        'crt_lt_2' => 'boolean',
        'bradikardia' => 'boolean',
        'stimulasi' => 'boolean',
    ];

    /**
     * Relasi ke induk/header monitoring
     */
    public function picuMonitoring(): BelongsTo
    {
        return $this->belongsTo(PicuMonitoring::class, 'picu_monitoring_id');
    }

    // Nanti kita tambahkan relasi ke petugas
}
