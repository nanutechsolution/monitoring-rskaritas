<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PicuParenteralIntake extends Model
{
    use HasFactory;

    protected $table = 'picu_parenteral_intakes';
    protected $fillable = [
        'monitoring_record_id',
        'name',
        'volume',
    ];

    public function monitoringRecord(): BelongsTo
    {
        return $this->belongsTo(PicuMonitoringRecord::class);
    }
}
