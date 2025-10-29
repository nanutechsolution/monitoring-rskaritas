<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuDevice;
use Livewire\Attributes\Computed;

class PicuDevices extends Component
{
    public $monitoringSheetId;

    // --- Properti untuk Form Input ---
    public $nama_alat;
    public $ukuran;
    public $lokasi;
    public $tanggal_pemasangan;

    // Opsi untuk dropdown
    public $alatOptions = [
        'PICC' => 'PICC (Peripheral Inserted Central Catheter)',
        'CVC' => 'CVC (Central Venous Catheter)',
        'Arteri Line' => 'Arteri Line',
        'NTT / ETT' => 'NTT / ETT',
        'NGT / OGT' => 'NGT / OGT',
        'WSD' => 'WSD (Water Seal Drainage)',
        'Drain' => 'Drain',
        'Urin Kateter' => 'Urin Kateter',
        'Luka' => 'Luka',
        'Lainnya' => 'Lainnya...',
    ];

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;
        $this->tanggal_pemasangan = now()->format('Y-m-d');
    }

    // Ambil data log alat
    #[Computed]
    public function devices()
    {
        return PicuDevice::where('picu_monitoring_id', $this->monitoringSheetId)
            ->orderBy('tanggal_pemasangan', 'desc')
            ->get();
    }

    /**
     * Simpan data alat baru
     */
    public function save()
    {
        $this->validate([
            'nama_alat'         => 'required|string|max:100',
            'ukuran'            => 'nullable|string|max:50',
            'lokasi'            => 'nullable|string|max:100',
            'tanggal_pemasangan' => 'required|date',
        ]);

        PicuDevice::create([
            'picu_monitoring_id' => $this->monitoringSheetId,
            'nama_alat'         => $this->nama_alat,
            'ukuran'            => $this->ukuran,
            'lokasi'            => $this->lokasi,
            'tanggal_pemasangan' => $this->tanggal_pemasangan,
            'petugas_id'        => auth()->user()->id_user, // Ganti dengan user Anda
        ]);

        session()->flash('success-device', 'Data alat terpasang berhasil disimpan.');

        // Reset form
        $this->resetForm();

        // Hapus cache computed property agar log ter-refresh
        unset($this->devices);
    }

    /**
     * Hapus data alat
     */
    public function delete($id)
    {
        PicuDevice::find($id)->delete();
        session()->flash('success-device', 'Data alat berhasil dihapus.');
        unset($this->devices); // Refresh log
    }

    /**
     * Helper untuk reset form
     */
    public function resetForm()
    {
        $this->reset('nama_alat', 'ukuran', 'lokasi');
        $this->tanggal_pemasangan = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.picu-devices')->layout('layouts.app');
    }
}
