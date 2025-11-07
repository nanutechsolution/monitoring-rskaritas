<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MonitoringRecord;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

class ObservasiTable extends Component
{

    public ?int $cycleId = null;
    public Collection $records;


    /**
     * INI PERBAIKANNYA: Tambahkan "?int"
     * Ini mengizinkan $cycleId untuk bernilai null saat dipanggil
     */
    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->records = new Collection(); // Inisialisasi koleksi kosong
        $this->loadRecords();
    }

    /**
     * TAMBAHKAN FUNGSI INI
     * Hook ini akan otomatis berjalan saat properti 'cycleId'
     * diperbarui oleh komponen parent (PatientMonitor).
     */
    public function updatedCycleId($newCycleId)
    {
        $this->cycleId = $newCycleId;
        $this->loadRecords();
    }

    /**
     * Ganti nama 'refreshTable' menjadi 'loadRecords'
     * agar bisa dipanggil oleh 'mount' DAN oleh listener.
     */
    #[On('record-saved')]
    public function loadRecords()
    {
        // Pengecekan ini SANGAT PENTING
        if (!$this->cycleId) {
            $this->records = new Collection(); // Pastikan tetap collection kosong
            return;
        }

        $this->records = MonitoringRecord::where('monitoring_cycle_id', $this->cycleId)
            ->with('pegawai') // Selalu Eager Load relasi yg Anda pakai di view
            ->orderByDesc('record_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.observasi-table');
    }
}
