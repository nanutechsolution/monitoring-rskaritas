<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PippAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_cycle_id',
        'id_user',
        'assessment_time',
        'gestational_age',
        'behavioral_state',
        'max_heart_rate',
        'min_oxygen_saturation',
        'brow_bulge',
        'eye_squeeze',
        'nasolabial_furrow',
        'total_score',
    ];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_user', 'nik');
    }

    public function getAuthorNameAttribute()
    {
        if ($this->id_user === 'admin') {
            return 'Admin Utama';
        }

        return $this->pegawai?->nama ?? $this->id_user;
    }
}
