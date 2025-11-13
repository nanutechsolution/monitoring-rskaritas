<?php

namespace App\Livewire\Picu;

use App\Models\PicuMonitoringRecord;
use App\Models\PicuMonitoringCycle;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ObservasiTable extends Component
{
    public string $no_rawat;
    public ?int $currentCycleId = null;
    public Collection $cycles;
    public $selectedFilterCycleId = null;

    public ?int $selectedRecordId = null;
    public $detailedRecord = null;
    public bool $showDetailModal = false;

    #[On('data-tersimpan-baru')]
    public function refreshTable(): void
    {
        $this->loadRecords();
    }

    public function mount(string $noRawat, $cycleId = null): void
    {
        $clean = trim($noRawat);
        $this->no_rawat = str_replace('_', '/', $clean);
        $this->selectedFilterCycleId = $cycleId;
        $this->cycles = new Collection();
        $this->loadCycles();
    }

    public function loadCycles(): void
    {
        if (empty($this->no_rawat)) {
            $this->cycles = new Collection();
            return;
        }

        // Ambil SEMUA siklus monitoring (24 jam) untuk pasien ini
        $this->cycles = PicuMonitoringCycle::where('no_rawat', $this->no_rawat)
            ->when($this->selectedFilterCycleId, function ($query, $cycleId) {
                return $query->where('id', $cycleId);
            })
            // Eager load records di dalamnya
            ->with([
                'records' => function ($query) {
                    // Urutkan records di dalam siklus dari yang terbaru ke terlama
                    $query->orderBy('record_time', 'desc');
                }
            ])
            ->withMin('records', 'hr')     // Min Heart Rate
            ->withMax('records', 'hr')     // Max Heart Rate
            ->withMin('records', 'sat_o2') // Min Saturasi O2
            ->withMax('records', 'sat_o2') // Max Saturasi O2
            ->withMin('records', 'temp_skin') // Min Suhu
            ->withMax('records', 'temp_skin') // Max Suhu

            // 3. AGREGASI EVENT KRITIS (Menggunakan MAX boolean untuk mendeteksi apakah pernah terjadi)
            ->withMax('records', 'cyanosis')
            ->withMax('records', 'bradikardia')
            // Urutkan siklus dari yang terbaru ke terlama
            ->orderBy('start_time', 'desc')
            ->get();

        // Tetapkan currentCycleId dari siklus paling atas (yang terbaru)
        if ($this->cycles->isNotEmpty()) {
            $this->currentCycleId = $this->cycles->first()->id;
        }
    }

    protected function getMetricList(): array
    {
        return [
            'author_name' => 'Diinput Oleh', // Sudah ada

            // === TTV UTAMA & DASAR ===
            'hr' => 'Detak Jantung (HR)', // Sudah ada
            'rr' => 'Laju Napas (RR)', // Sudah ada
            'temp_skin' => 'Suhu Tubuh (°C)', // Sudah ada
            'sat_o2' => 'SpO₂ (%)', // Sudah ada
            'tensi_combined' => 'Tekanan Darah (S/D) mmHg', // Sudah ada
            'irama_ekg' => 'Irama EKG', // BARU
            'skala_nyeri' => 'Skala Nyeri', // BARU
            'humidifier_inkubator' => 'Humidifier Inkubator', // BARU
            'critical_events_summary' => 'Status Kritis (Events)',
            // === VENTILATOR SETTINGS LENGKAP ===
            'monitor_mode' => 'Vent. Mode', // BARU
            'monitor_peep' => 'Vent. PEEP', // Sudah ada
            'monitor_pip' => 'Vent. PIP', // BARU
            'monitor_fio2' => 'Vent. FiO₂', // Sudah ada
            'monitor_tv_vte' => 'Vent. TV/Vte (ml)', // BARU
            'monitor_rr_spontan' => 'Vent. RR Spontan', // BARU
            'monitor_p_max' => 'Vent. P.Max', // BARU
            'monitor_ie' => 'Vent. I:E', // BARU

            // === CAIRAN MASUK & KELUAR LENGKAP ===
            'intake_ogt' => 'Intake OGT (cc)', // BARU
            'intake_oral' => 'Intake Oral (cc)', // BARU
            'output_urine' => 'Output Urine (cc)', // BARU
            'output_bab' => 'Output BAB (cc)', // BARU
            'output_residu' => 'Output Residu (cc)', // BARU
            'output_ngt' => 'Output NGT (cc)', // BARU
            'output_drain' => 'Output Drain (cc)', // BARU

            // === SPONTAN & CPAP (Opsional, jika ingin ditampilkan terpisah dari monitor mode) ===
            // Note: Sebaiknya gunakan kolom 'monitor_' yang lebih spesifik jika data diisi di sana.
            // 'spontan_fio2' => 'Spontan FiO₂',
            // 'cpap_peep' => 'CPAP PEEP',
        ];
    }

    public function openDetails(int $recordId): void
    {
        $this->detailedRecord = PicuMonitoringRecord::find($recordId);
        $this->selectedRecordId = $recordId;
        $this->showDetailModal = true;
    }
    public function closeDetails(): void
    {
        $this->reset(['showDetailModal', 'detailedRecord', 'selectedRecordId']);
    }

    public function placeholder(): string
    {
        // Placeholder tetap sama
        return <<<HTML
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-6 mt-12">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    <div class="animate-pulse flex space-x-4 justify-center">
                        <div class="rounded-full bg-gray-300 dark:bg-gray-600 h-10 w-10"></div>
                        <div class="flex-1 space-y-3 py-1 text-left">
                            <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded"></div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded col-span-2"></div>
                                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded col-span-1"></div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4">Memuat riwayat observasi...</p>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render(): View
    {
        return view('livewire.picu.observasi-table');
    }
}
