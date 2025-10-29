<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PicuMonitoring extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Relasi ke detail cycle per jam
     */
    public function cycles(): HasMany
    {
        return $this->hasMany(PicuCycle::class, 'picu_monitoring_id');
    }
    /**
     * Relasi baru ke log AGD
     */
    public function bloodGasLogs(): HasMany
    {
        return $this->hasMany(PicuBloodGasLog::class, 'picu_monitoring_id')
            ->orderBy('waktu_log', 'desc');
    }

    /**
     * Relasi baru ke log cairan masuk
     */
    public function fluidInputs(): HasMany
    {
        return $this->hasMany(PicuFluidInput::class, 'picu_monitoring_id')
            ->orderBy('waktu_log', 'desc');
    }

    /**
     * Relasi baru ke log cairan keluar
     */
    public function fluidOutputs(): HasMany
    {
        return $this->hasMany(PicuFluidOutput::class, 'picu_monitoring_id')
            ->orderBy('waktu_log', 'desc');
    }


    /**
     * Relasi baru ke log obat-obatan
     */
    public function medicationLogs(): HasMany
    {
        return $this->hasMany(PicuMedicationLog::class, 'picu_monitoring_id')
            ->orderBy('waktu_pemberian', 'desc');
    }

    /**
     * Relasi baru ke log alat terpasang
     */
    public function devices(): HasMany
    {
        return $this->hasMany(PicuDevice::class, 'picu_monitoring_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Relasi baru ke tabel dokter (Khanza)
     * Asumsi: foreign key 'dokter_dpjp' terhubung ke 'kd_dokter'
     */
    public function dokter(): BelongsTo
    {
        // Ganti 'App\Models\Dokter' jika model dokter Anda berbeda
        // Ganti 'kd_dokter' jika primary key tabel dokter berbeda
        return $this->belongsTo(Dokter::class, 'dokter_dpjp', 'kd_dokter');
    }
}
