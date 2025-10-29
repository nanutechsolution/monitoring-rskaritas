<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringDevicePicu extends Model
{
    use HasFactory;

    protected $table = 'monitoring_devices_picu';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pasang' => 'date',
    ];

    /**
     * Relasi ke lembar observasi induk.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(MonitoringCyclePicu::class, 'monitoring_cycle_picu_id');
    }
}
