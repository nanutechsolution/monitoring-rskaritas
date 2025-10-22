<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDevice extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'no_rawat',
        'device_name',
        'size',
        'location',
        'installation_date',
        'removal_date',           // <-- [PERBAIKAN] Ini wajib ada
        'installed_by_user_id',
        'removed_by_user_id'
    ];

    /**
     * Otomatis ubah kolom tanggal menjadi objek Carbon.
     */
    protected $casts = [
        'installation_date' => 'datetime',
        'removal_date' => 'datetime',
    ];

    /**
     * [BARU] Relasi untuk mengambil data pegawai yang MEMASANG.
     * * Foreign key: installed_by_user_id
     * Owner key: nik (di tabel pegawai)
     */
    public function installer()
    {
        return $this->belongsTo(Pegawai::class, 'installed_by_user_id', 'nik');
    }

    /**
     * [BARU] Relasi untuk mengambil data pegawai yang MELEPAS.
     *
     * Foreign key: removed_by_user_id
     * Owner key: nik (di tabel pegawai)
     */
    public function remover()
    {
        return $this->belongsTo(Pegawai::class, 'removed_by_user_id', 'nik');
    }

    /**
     * [BARU] Accessor untuk mendapatkan nama petugas pemasang.
     *
     * Cara panggil di Blade: $device->installer_name
     */
    public function getInstallerNameAttribute()
    {
        if ($this->installed_by_user_id === 'admin') {
            return 'Admin';
        }

        // Ambil dari relasi 'installer'
        return $this->installer?->nama ?? $this->installed_by_user_id;
    }

    /**
     * [BARU] Accessor untuk mendapatkan nama petugas pelepas.
     *
     * Cara panggil di Blade: $device->remover_name
     */
    public function getRemoverNameAttribute()
    {
        if ($this->removed_by_user_id === 'admin') {
            return 'Admin';
        }

        // Ambil dari relasi 'remover'
        return $this->remover?->nama ?? $this->removed_by_user_id;
    }
}
