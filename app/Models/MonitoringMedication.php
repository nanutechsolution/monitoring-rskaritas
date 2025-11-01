<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringMedication extends Model
{
    use HasFactory;

    /**
     * 💡 TAMBAHKAN INI
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'waktu',
        'nama_obat_infus_gas',
        'dosis',
        'rute',
    ];

    /**
     * 💡 TAMBAHKAN INI JUGA
     * Beri tahu Laravel bahwa tabel ini tidak memiliki
     * kolom created_at dan updated_at.
     */
    public $timestamps = false;


    public function intraAnesthesiaMonitoring()
    {
        return $this->belongsTo(IntraAnesthesiaMonitoring::class);
    }
}
