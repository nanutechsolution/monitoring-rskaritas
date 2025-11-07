<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PicuMonitoringRecord extends Model
{
    use HasFactory;


    protected $table = 'picu_monitoring_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'monitoring_cycle_id',
        'id_user',
        'record_time',

        // Hemodinamik Fields
        'temp_incubator',
        'temp_skin',
        'hr',
        'rr',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'sat_o2',

        // Extra Hemodynamic Fields
        'irama_ekg',
        'skala_nyeri', // <-- Nama kolom di migrasi terakhir adalah 'skala_nyeri'
        'humidifier_inkubator',

        // Observasi Warna & Apnea Fields
        'cyanosis',
        'pucat',
        'ikterus',
        'crt_less_than_2',
        'bradikardia',
        'stimulasi',

        // Ventilator Fields (INI YANG PALING PENTING DITAMBAHKAN)
        'respiratory_mode',
        'spontan_fio2',
        'spontan_flow',
        'cpap_fio2',
        'cpap_flow',
        'cpap_peep',
        'hfo_fio2',
        'hfo_frekuensi',
        'hfo_map',
        'hfo_amplitudo',
        'hfo_it',
        'monitor_mode',
        'monitor_fio2',
        'monitor_peep',
        'monitor_pip',
        'monitor_tv_vte',
        'monitor_rr_spontan',
        'monitor_p_max',
        'monitor_ie',
        'intake_ogt',
        'intake_oral',
        'output_urine',
        'output_bab',
        'output_residu',
        'output_ngt',
        'output_drain',
    ];

    public function parenteralIntakes(): HasMany
    {
        return $this->hasMany(PicuParenteralIntake::class, 'monitoring_record_id');
    }

    public function enteralIntakes()
    {
        return $this->hasMany(PicuEnteralIntake::class, 'monitoring_record_id');
    }

    public function totalParenteral()
    {
        return $this->parenteralIntakes->sum('volume');
    }
    public function totalEnteralIntakes()
    {
        return $this->enteralIntakes->sum('volume');
    }

    public function totalCairanMasuk()
    {
        return $this->totalParenteral() + $this->totalEnteralIntakes() + $this->intake_ogt + $this->intake_oral;
    }

    public function totalCairanKeluar()
    {
        return $this->output_ngt + $this->output_urine + $this->output_bab + $this->output_drain;
    }

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
