<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MonitoringCycleIcu extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     * * @var string
     */
    protected $table = 'monitoring_cycle_icu'; // Pastikan nama tabelnya benar

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     * * @var array<int, string>
     */
    protected $guarded = ['id']; // Kita jaga 'id' saja, sisanya boleh diisi

    /**
     * Tipe data untuk atribut tertentu (casting).
     */
    protected $casts = [
        'sheet_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // --- DEFINISI RELASI ---

    /**
     * Relasi one-to-many: Satu lembar cycle memiliki BANYAK record per jam.
     */
    public function records(): HasMany
    {
        // Memanggil model MonitoringRecordIcu, terhubung via foreign key 'monitoring_cycle_icu_id'
        return $this->hasMany(MonitoringRecordIcu::class, 'monitoring_cycle_icu_id');
    }

    /**
     * Relasi many-to-many: Satu lembar cycle bisa memiliki BANYAK DPJP (Dokter).
     */
    public function dpjpDokter(): BelongsToMany
    {
        // Model Dokter,
        // nama tabel pivot,
        // foreign key tabel ini,
        // foreign key tabel dokter
        return $this->belongsToMany(
            Dokter::class,
            'dpjp_monitoring_cycle_icu',
            'monitoring_cycle_icu_id',
            'kd_dokter'
        );
    }

    /**
     * Relasi one-to-one (inverse): Satu lembar cycle milik SATU data registrasi.
     */
    public function registrasi(): BelongsTo
    {
        // Memanggil model RegPeriksa, terhubung via foreign key 'no_rawat'
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
