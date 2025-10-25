<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TherapyProgram extends Model
{
    use HasFactory;

    /**
     * Izinkan pengisian massal untuk semua kolom kecuali 'id'.
     * Ini cara cepat untuk $guarded.
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke siklus monitoring induknya.
     */
    public function cycle()
    {
        return $this->belongsTo(MonitoringCycle::class, 'monitoring_cycle_id');
    }

    /**
     * Relasi untuk mengambil data pegawai yang menulis.
     * Kita asumsikan 'id_user' di tabel ini adalah 'nik' di tabel 'pegawai'.
     */
    public function pegawai()
    {
        // 'id_user' = foreign key di tabel 'therapy_programs'
        // 'nik' = primary key di tabel 'pegawai'
        return $this->belongsTo(Pegawai::class, 'id_user', 'nik');
    }

    /**
     * Helper (Accessor) untuk mendapatkan nama penulis.
     * * Ini adalah best practice. Di Blade, kita bisa panggil:
     * $program->author_name
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
            return 'Admin Utama'; // Ini adalah admin!
        }

        // 3. Jika bukan pegawai DAN bukan admin,
        //    kembalikan id_user-nya sebagai fallback.
        return $this->id_user;
    }
}
