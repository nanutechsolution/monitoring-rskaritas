<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringRecordPicu extends Model
{
    use HasFactory;

    protected $table = 'monitoring_record_picu';
    protected $guarded = ['id'];

    protected $casts = [
        'observation_time' => 'datetime',
        'is_enteral' => 'boolean',
        'is_parenteral' => 'boolean',
    ];

    /**
     * Relasi ke lembar observasi induk.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(MonitoringCyclePicu::class, 'monitoring_cycle_picu_id');
    }

    /**
     * Relasi ke pegawai (inputter).
     */
    public function inputter(): BelongsTo
    {
        // Asumsi Model User Anda (KhanzaUser) memiliki 'nik'
        // Jika tidak, pastikan relasi ke 'pegawai' benar
        return $this->belongsTo(Pegawai::class, 'nik_inputter', 'nik');
    }

    /**
     * Relasi baru ke tabel dokter (Khanza)
     * Asumsi: foreign key 'dokter_dpjp' terhubung ke 'kd_dokter'
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_dpjp', 'kd_dokter');
    }
}
