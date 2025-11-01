<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringVital extends Model
{
    use HasFactory;

    /**
     * TAMBAHKAN INI
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'waktu',
        'rrn',
        'td_sis',
        'td_dis',
        'rr',
        'spo2',
        'pe_co2',
        'fio2',
        'lain_lain',
    ];

    /**
     * Buat tabel ini tidak menggunakan timestamps (created_at/updated_at)
     * karena kita hanya peduli pada kolom 'waktu'.
     */
    public $timestamps = false;


    public function intraAnesthesiaMonitoring()
    {
        return $this->belongsTo(IntraAnesthesiaMonitoring::class);
    }
}
