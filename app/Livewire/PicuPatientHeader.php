<?php

namespace App\Livewire;

use App\Models\DiagnosaPasien;
use App\Models\DpjpRanap;
use Livewire\Component;
use App\Models\PicuMonitoring; // Import model
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PicuPatientHeader extends Component
{
    public $regPeriksa;
    public PicuMonitoring $monitoringSheet;
    // Properti baru untuk menampung LIST dokter
    public Collection $dpjpList;
    public Collection $diagnosaList;
    public $hariRawat;

    public $diagnosis;
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
        $this->dpjpList = DpjpRanap::where('no_rawat', $this->regPeriksa->no_rawat)
            ->with(relations: 'dokter')
            ->get();

        $this->diagnosaList = DiagnosaPasien::where('no_rawat', $this->regPeriksa->no_rawat)
            ->where('status', 'Ranap') // Hanya ambil yg Ranap
            ->with('penyakit') // Eager load nama penyakit
            ->orderBy('prioritas', 'asc') // Urutkan
            ->get();
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
            'umur_kehamilan' => 'nullable|string|max:20',
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
