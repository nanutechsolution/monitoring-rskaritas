<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMonitoring; // Import model
use Carbon\Carbon;

class PicuPatientHeader extends Component
{
    public $regPeriksa;
    public PicuMonitoring $monitoringSheet; // Gunakan type-hint untuk model
    public $hariRawat;

    // --- Properti Baru untuk Form Input ---
    // Field-field ini akan di-bind ke form
    public $diagnosis;
    public $dokter_dpjp; // Kita biarkan ini read-only untuk saat ini
    public $umur_kehamilan;
    public $umur_koreksi;
    public $berat_badan_lahir;
    public $cara_persalinan;
    public $rujukan;
    public $asal_ruangan;
    public $jaminan;

    public function mount($regPeriksa, PicuMonitoring $monitoringSheet)
    {
        $this->regPeriksa = $regPeriksa;
        $this->monitoringSheet = $monitoringSheet;

        // Hitung Hari Rawat Ke
        $this->hariRawat = Carbon::parse($this->regPeriksa->tgl_registrasi)
                                 ->diffInDays(now()) + 1;

        // Isi public properties dari data monitoringSheet yang ada
        $this->fill($this->monitoringSheet->toArray());
    }

    /**
     * Fungsi ini akan dipanggil SETIAP KALI user
     * selesai mengedit sebuah input field (on blur).
     */
    public function saveHeader()
    {
        // Ambil hanya data yang relevan untuk di-update
        $dataToSave = $this->only([
            'diagnosis',
            'umur_kehamilan',
            'umur_koreksi',
            'berat_badan_lahir',
            'cara_persalinan',
            'rujukan',
            'asal_ruangan',
            'jaminan',
        ]);

        // Validasi (opsional tapi disarankan)
        $this->validate([
            'diagnosis' => 'nullable|string|max:100',
            'umur_kehamilan' => 'nullable|string|max:20',
            // ... validasi lain jika perlu
        ]);

        // Update data di database
        $this->monitoringSheet->update($dataToSave);

        // Beri notifikasi (opsional)
        session()->flash('success-header', 'Data header berhasil diupdate.');
    }

    public function render()
    {
        return view('livewire.picu-patient-header')->layout('layouts.app');
    }
}
