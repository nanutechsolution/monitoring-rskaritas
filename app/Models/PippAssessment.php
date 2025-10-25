<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
