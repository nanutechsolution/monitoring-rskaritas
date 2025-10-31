<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosaPasien extends Model
{
    use HasFactory;

    protected $table = 'diagnosa_pasien';
    
    // Composite primary key
    public $incrementing = false;
    public $timestamps = false;

    /**
     * Relasi untuk mengambil data penyakit (nama, dll)
     */
    public function penyakit(): BelongsTo
    {
        // Ganti 'App\Models\Penyakit' jika model Anda berbeda
        return $this->belongsTo(Penyakit::class, 'kd_penyakit', 'kd_penyakit');
    }
}