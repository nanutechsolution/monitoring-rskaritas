<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodGasResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_cycle_id',
        'id_user',
        'taken_at',
        'gula_darah',
        'ph',
        'pco2',
        'po2',
        'hco3',
        'be',
        'sao2',
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
