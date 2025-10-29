<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMedicationLog;
use Livewire\Attributes\Computed;

class PicuMedications extends Component
{
    public $monitoringSheetId;

    // --- Properti untuk Form Input ---
    public $waktu_pemberian;
    public $nama_obat;
    public $dosis;
    public $rute;

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;
        $this->waktu_pemberian = now()->format('Y-m-d\TH:i');
    }

    // Ambil data log obat-obatan
    #[Computed]
    public function medicationLogs()
    {
        return PicuMedicationLog::where('picu_monitoring_id', $this->monitoringSheetId)
            ->orderBy('waktu_pemberian', 'desc')
            ->get();
    }

    /**
     * Simpan data obat baru
     */
    public function save()
    {
        $this->validate([
            'waktu_pemberian' => 'required|date',
            'nama_obat'       => 'required|string|max:150',
            'dosis'           => 'required|string|max:50',
            'rute'            => 'required|string|max:50',
        ]);

        PicuMedicationLog::create([
            'picu_monitoring_id' => $this->monitoringSheetId,
            'waktu_pemberian' => $this->waktu_pemberian,
            'nama_obat'       => $this->nama_obat,
            'dosis'           => $this->dosis,
            'rute'            => $this->rute,
            'petugas_id'      => auth()->user()->id_user, // Ganti dengan user Anda
        ]);

        session()->flash('success-med', 'Data obat berhasil disimpan.');

        // Reset form
        $this->resetForm();

        // Hapus cache computed property agar log ter-refresh
        unset($this->medicationLogs);
    }

    /**
     * Helper untuk reset form
     */
    public function resetForm()
    {
        $this->reset('nama_obat', 'dosis', 'rute');
        $this->waktu_pemberian = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        return view('livewire.picu-medications')->layout('layouts.app');
    }
}
