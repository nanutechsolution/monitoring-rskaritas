<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicuEnteralIntake extends Model
{
    use HasFactory;

    protected $tabel = 'picu_enteral_intakes';


    protected $fillable = [
        'monitoring_record_id',
        'name',
        'volume',
    ];

    /**
     * Relasi ke tabel monitoring_records
     * Setiap entri enteral intake milik satu monitoring record
     */
    public function monitoringRecord()
    {
        return $this->belongsTo(PicuMonitoringRecord::class);
    }
}
