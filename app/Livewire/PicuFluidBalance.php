<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMonitoring;
use App\Models\PicuFluidInput;
use App\Models\PicuFluidOutput;
use Livewire\Attributes\Computed;

class PicuFluidBalance extends Component
{
    public $monitoringSheetId;
    public $monitoringSheet; // Kita simpan instance induk

    // --- Properti untuk Form Input ---
    public $type = 'input'; // 'input' or 'output'
    public $waktu_log;
    public $kategori;
    public $keterangan;
    public $jumlah;

    // --- Properti untuk Summary (Bisa Diedit) ---
    // Ini di-bind ke tabel picu_monitorings
    public $balance_cairan_24h_sebelumnya;
    public $ewl_24h;

    // --- Properti untuk Tampilan Summary (Read-Only) ---
    public $totalMasuk = 0;
    public $totalKeluar = 0;
    public $balanceHarian = 0;
    public $balanceKumulatif = 0;
    public $produksiUrine = 0;

    // Opsi untuk dropdown form
    public $kategoriInput = [
        'PARENTERAL' => 'Parenteral (Infus, Obat)',
        'OGT' => 'Nutrisi (OGT)',
        'ORAL' => 'Oral',
    ];
    public $kategoriOutput = [
        'URINE' => 'Urine',
        'NGT' => 'NGT (Residu)',
        'BAB' => 'BAB',
        'DRAIN' => 'Drain',
    ];

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;
        $this->waktu_log = now()->format('Y-m-d\TH:i');

        // Muat data summary dari tabel induk
        $this->monitoringSheet = PicuMonitoring::find($this->monitoringSheetId);
        $this->balance_cairan_24h_sebelumnya = $this->monitoringSheet->balance_cairan_24h_sebelumnya ?? 0;
        $this->ewl_24h = $this->monitoringSheet->ewl_24h ?? 0;

        // Hitung semua total saat load
        $this->refreshCalculations();
    }

    // Ambil data log input
    #[Computed]
    public function fluidInputs()
    {
        return PicuFluidInput::where('picu_monitoring_id', $this->monitoringSheetId)
            ->orderBy('waktu_log', 'desc')
            ->get();
    }

    // Ambil data log output
    #[Computed]
    public function fluidOutputs()
    {
        return PicuFluidOutput::where('picu_monitoring_id', $this->monitoringSheetId)
            ->orderBy('waktu_log', 'desc')
            ->get();
    }

    /**
     * Menyimpan LOG BARU (Input atau Output)
     */
    public function saveLog()
    {
        $this->validate([
            'waktu_log'  => 'required|date',
            'kategori'   => 'required|string',
            'keterangan' => 'required|string|max:100',
            'jumlah'     => 'required|numeric|min:0',
        ]);

        $data = [
            'picu_monitoring_id' => $this->monitoringSheetId,
            'waktu_log'  => $this->waktu_log,
            'kategori'   => $this->kategori,
            'keterangan' => $this->keterangan,
            'jumlah'     => $this->jumlah,
            'petugas_id' => auth()->user()->id_user,
        ];

        if ($this->type == 'input') {
            PicuFluidInput::create($data);
            session()->flash('success-fluid', 'Data cairan MASUK berhasil disimpan.');
        } else {
            PicuFluidOutput::create($data);
            session()->flash('success-fluid', 'Data cairan KELUAR berhasil disimpan.');
        }

        // Hitung ulang semua
        $this->refreshCalculations();

        // Reset form
        $this->reset('kategori', 'keterangan', 'jumlah');
        $this->waktu_log = now()->format('Y-m-d\TH:i');
    }

    /**
     * Menyimpan SUMMARY (EWL, Balance Sebelumnya)
     * wire:change.debounce="updateSummary" akan memanggil ini
     */
    public function updateSummary()
    {
        $this->monitoringSheet->update([
            'balance_cairan_24h_sebelumnya' => $this->balance_cairan_24h_sebelumnya,
            'ewl_24h' => $this->ewl_24h,
        ]);

        // Hitung ulang dengan data baru
        $this->refreshCalculations();
        session()->flash('success-fluid', 'Data summary (EWL/Balance Sblm) diupdate.');
    }

    /**
     * INTI LOGIKA: Menghitung semua total
     * dan menyimpannya ke tabel induk (picu_monitorings)
     */
    #[On('refresh-fluid-balance')]
    public function refreshCalculations()
    {
        // 1. Hapus cache computed property agar ter-query ulang
        unset($this->fluidInputs);
        unset($this->fluidOutputs);

        // 2. Hitung total dari log
        $this->totalMasuk = $this->fluidInputs()->sum('jumlah');
        $this->totalKeluar = $this->fluidOutputs()->sum('jumlah');
        $this->produksiUrine = $this->fluidOutputs()->where('kategori', 'URINE')->sum('jumlah');

        // 3. Hitung balance
        $this->balanceHarian = $this->totalMasuk - $this->totalKeluar - ($this->ewl_24h ?? 0);
        $this->balanceKumulatif = $this->balanceHarian + ($this->balance_cairan_24h_sebelumnya ?? 0);

        // 4. SIMPAN HASIL KALKULASI ke tabel induk
        $this->monitoringSheet->update([
            'total_cairan_masuk_24h' => $this->totalMasuk,
            'total_cairan_keluar_24h' => $this->totalKeluar,
            'produksi_urine_24h' => $this->produksiUrine,
            'balance_cairan_24h' => $this->balanceHarian,
            // ewl & balance sebelumnya sudah di-save oleh updateSummary()
        ]);
    }

    public function render()
    {
        return view('livewire.picu-fluid-balance')->layout('layouts.app');
    }
}
