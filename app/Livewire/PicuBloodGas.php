<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuBloodGasLog;
use App\Models\PicuMonitoring;
use Livewire\Attributes\On;

class PicuBloodGas extends Component
{
    public $monitoringSheetId;
    public $logs;

    // --- Properti untuk Form Input ---
    public $waktu_log;
    public $guka_darah_bs;
    public $ph;
    public $pco2;
    public $po2;
    public $hco3;
    public $be;
    public $sao2;

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;

        // Inisialisasi waktu ke "sekarang"
        $this->waktu_log = now()->format('Y-m-d\TH:i');

        // Muat log yang sudah ada
        $this->loadLogs();
    }

    /**
     * Muat/Refresh log dari database
     */
    #[On('blood-gas-updated')] // Listener untuk refresh
    public function loadLogs()
    {
        $this->logs = PicuBloodGasLog::where('picu_monitoring_id', $this->monitoringSheetId)
            ->orderBy('waktu_log', 'desc')
            ->get();
    }

    /**
     * Simpan data AGD baru
     */
    public function save()
    {
        $this->validate([
            'waktu_log'       => 'required|date',
            'guka_darah_bs'   => 'nullable|numeric',
            'ph'              => 'nullable|numeric',
            'pco2'            => 'nullable|numeric',
            'po2'             => 'nullable|numeric',
            'hco3'            => 'nullable|numeric',
            'be'              => 'nullable|numeric',
            'sao2'            => 'nullable|numeric',
        ]);

        PicuBloodGasLog::create([
            'picu_monitoring_id' => $this->monitoringSheetId,
            'waktu_log'       => $this->waktu_log,
            'guka_darah_bs'   => $this->guka_darah_bs,
            'ph'              => $this->ph,
            'pco2'            => $this->pco2,
            'po2'             => $this->po2,
            'hco3'            => $this->hco3,
            'be'              => $this->be,
            'sao2'            => $this->sao2,
            'petugas_id'      => auth()->user()->id_user, // Ganti dengan user Anda
        ]);

        session()->flash('success-agd', 'Data Blood Gas berhasil disimpan.');

        // Reset form
        $this->resetForm();

        // Refresh daftar log
        $this->loadLogs();
    }

    /**
     * Helper untuk reset form
     */
    public function resetForm()
    {
        $this->reset(
            'guka_darah_bs',
            'ph',
            'pco2',
            'po2',
            'hco3',
            'be',
            'sao2'
        );
        $this->waktu_log = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        return view('livewire.picu-blood-gas')->layout('layouts.app');
    }
}
