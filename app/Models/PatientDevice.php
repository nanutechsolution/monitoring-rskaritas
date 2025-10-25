<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * [ACCESSOR]
     * Mendapatkan nama installer (Pegawai atau Admin).
     * Akan dipanggil oleh: $device->installer_name
     */
    public function getInstallerNameAttribute()
    {
        // 1. Cek relasi 'installer' (ke tabel pegawai) dulu.
        //    ($this->installer merujuk ke fungsi relasi installer())
        $pegawaiName = $this->installer?->nama;

        if ($pegawaiName) {
            return $pegawaiName; // Ditemukan! Langsung kembalikan.
        }

        // 2. Jika TIDAK ditemukan di pegawai, cek ke tabel 'admin'.
        //    ($this->installed_by_user_id adalah nama kolom di tabel ini)
        $isAdmin = DB::table('admin')
                      ->whereRaw("usere = AES_ENCRYPT(?, 'nur')", [$this->installed_by_user_id])
                      ->exists();

        if ($isAdmin) {
            return 'Admin Utama'; // Ini adalah admin!
        }

        // 3. Fallback: kembalikan ID aslinya.
        return $this->installed_by_user_id;
    }

    /**
     * [BARU] Accessor untuk mendapatkan nama petugas pemasang.
     *
     * Cara panggil di Blade: $device->installer_name
     */
     public function getAuthorNameAttribute()
    {
        // 1. Cek relasi 'pegawai' dulu.
        //    Ini adalah operasi yang cepat, terutama jika di-eager-load.
        $pegawaiName = $this->pegawai?->nama;

        if ($pegawaiName) {
            return $pegawaiName; // Ditemukan! Langsung kembalikan.
        }

        // 2. Jika TIDAK ditemukan di pegawai (null),
        //    baru kita cek ke tabel 'admin' (karena ini query ke DB).
        $isAdmin = DB::table('admin')
                      ->whereRaw("usere = AES_ENCRYPT(?, 'nur')", [$this->id_user])
                      ->exists();

        if ($isAdmin) {
            return 'Admin Utama';
        }

        // 3. Jika bukan pegawai DAN bukan admin,
        //    kembalikan id_user-nya sebagai fallback.
        return $this->id_user;
    }

    /**
     * [BARU] Accessor untuk mendapatkan nama petugas pelepas.
     *
     * Cara panggil di Blade: $device->remover_name
     */
    /**
     * [ACCESSOR]
     * Mendapatkan nama remover (Pegawai atau Admin).
     * Akan dipanggil oleh: $device->remover_name
     */
    public function getRemoverNameAttribute()
    {
        // 1. Cek relasi 'remover' (ke tabel pegawai) dulu.
        $pegawaiName = $this->remover?->nama;

        if ($pegawaiName) {
            return $pegawaiName; // Ditemukan!
        }

        // 2. Jika TIDAK ditemukan di pegawai, cek ke tabel 'admin'.
        //    ($this->removed_by_user_id adalah nama kolom di tabel ini)
        $isAdmin = DB::table('admin')
                      ->whereRaw("usere = AES_ENCRYPT(?, 'nur')", [$this->removed_by_user_id])
                      ->exists();

        if ($isAdmin) {
            return 'Admin Utama'; // Ini adalah admin!
        }

        // 3. Fallback: kembalikan ID aslinya.
        return $this->removed_by_user_id;
    }
}
