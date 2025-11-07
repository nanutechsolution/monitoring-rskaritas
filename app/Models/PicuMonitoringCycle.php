<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicuMonitoringCycle extends Model
{
    use HasFactory;

    protected $table = 'picu_monitoring_cycles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_rawat',
        'therapy_program',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime', // jika ada
    ];


    /**
     * Relasi ke SEMUA riwayat program terapi siklus ini.
     * Kita urutkan dari yang terbaru (desc).
     */
    public function therapyPrograms()
    {
        return $this->hasMany(PicuTherapyProgram::class, 'monitoring_cycle_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Relasi HANYA untuk mengambil 1 program terapi TERBARU.
     */
    public function latestTherapyProgram()
    {
        return $this->hasOne(PicuTherapyProgram::class, 'monitoring_cycle_id')
            ->latestOfMany(); // Otomatis ambil yg 'created_at' terbaru
    }
}
