<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_cycle_id',
        'id_user',
        'medication_name',
        'dose',
        'route',
        'given_at',
    ];
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
        // 1. Handle kasus 'admin' secara khusus
        if ($this->id_user === 'admin') {
            return 'Admin Utama';
        }

        // 2. Ambil nama dari relasi 'pegawai'
        // 3. Jika tidak ada (data lama/aneh), tampilkan 'id_user'-nya
        return $this->pegawai?->nama ?? $this->id_user;
    }
}
