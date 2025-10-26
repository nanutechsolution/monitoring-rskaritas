<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringRecordIcu extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * * @var string
     */
    protected $table = 'monitoring_record_icu'; // Pastikan nama tabelnya benar

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     * * @var array<int, string>
     */
    protected $guarded = ['id'];

    protected $casts = [
        'observation_time' => 'datetime', // Otomatis ubah string jadi objek Carbon
    ];
    // --- DEFINISI RELASI ---

    /**
     * Relasi one-to-many (inverse): Satu record per jam milik SATU lembar cycle.
     */
    public function cycle(): BelongsTo
    {
        // Memanggil model MonitoringCycleIcu, terhubung via foreign key 'monitoring_cycle_icu_id'
        return $this->belongsTo(MonitoringCycleIcu::class, 'monitoring_cycle_icu_id');
    }

    /**
     * Relasi one-to-one (inverse): Satu record inputan milik SATU pegawai (inputter).
     */
    public function inputter(): BelongsTo
    {
        // Memanggil model Pegawai, terhubung via foreign key 'nik_inputter'
        return $this->belongsTo(Pegawai::class, 'nik_inputter', 'nik');
    }
}
