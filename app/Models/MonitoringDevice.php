<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringDevice extends Model
{
    use HasFactory;

    protected $table = 'monitoring_devices';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pasang' => 'date',
    ];

    /**
     * Relasi ke lembar observasi induk.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(MonitoringCycleIcu::class, 'monitoring_cycle_icu_id');
    }
}
