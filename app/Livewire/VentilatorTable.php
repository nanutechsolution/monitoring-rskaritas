<?php

namespace App\Livewire;

use App\Models\MonitoringRecord;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class VentilatorTable extends Component
{
    public ?int $cycleId = null;
    public Collection $records;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->records = new Collection();
        // HAPUS INI: $this->loadVentilatorRecords();
        // Biarkan 'lazy' load dan listener 'cycle-updated' yang bekerja.
    }

    /**
     * Listener untuk event 'cycle-updated' dari parent.
     * Ini akan memperbaiki masalah 'lazy' load.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->loadVentilatorRecords();
    }

    /**
     * Listener untuk refresh (jika ada data baru disimpan)
     */
    #[On('record-saved')]
    public function loadVentilatorRecords()
    {
        if (!$this->cycleId) {
            $this->records = new Collection();
            return;
        }

        // P.S. SAYA KEMBALIKAN FILTER VENTILATOR ANDA DARI SEBELUMNYA.
        // Kode yang Anda kirim barusan mengambil SEMUA record,
        // yang sepertinya salah untuk "VentilatorTable".
        $this->records = MonitoringRecord::where('monitoring_cycle_id', $this->cycleId)
            ->where(function ($query) {
                $query->whereNotNull('respiratory_mode')
                    ->orWhereNotNull('monitor_mode')
                    ->orWhereNotNull('spontan_fio2')
                    ->orWhereNotNull('cpap_fio2')
                    ->orWhereNotNull('hfo_fio2');
            })
            ->with('pegawai')
            ->orderByDesc('record_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.patient-monitor.partials.output-tabel-ventilator');
    }
}
