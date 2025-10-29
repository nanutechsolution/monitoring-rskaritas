<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PicuDevice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['tanggal_pemasangan' => 'date'];

    public function picuMonitoring(): BelongsTo
    {
        return $this->belongsTo(PicuMonitoring::class, 'picu_monitoring_id');
    }
}
