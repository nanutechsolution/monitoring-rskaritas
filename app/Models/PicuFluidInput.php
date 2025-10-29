<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PicuFluidInput extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['waktu_log' => 'datetime'];

    public function picuMonitoring(): BelongsTo
    {
        return $this->belongsTo(PicuMonitoring::class, 'picu_monitoring_id');
    }
}
