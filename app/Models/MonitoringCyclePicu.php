<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringCyclePicu extends Model
{
    use HasFactory;

    protected $table = 'monitoring_cycle_picu';
    protected $guarded = ['id'];

    protected $casts = [
        'sheet_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Relasi ke data registrasi (untuk info pasien, dpjp, dll).
     */
    public function registrasi(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Relasi ke log input real-time (anak).
     */
    public function records(): HasMany
    {
        return $this->hasMany(MonitoringRecordPicu::class, 'monitoring_cycle_picu_id');
    }

    /**
     * Relasi ke alat/tube yang terpasang (anak).
     */
    public function devices(): HasMany
    {
        return $this->hasMany(MonitoringDevicePicu::class, 'monitoring_cycle_picu_id');
    }
}
