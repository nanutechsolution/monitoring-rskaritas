<?php

namespace App\Livewire\Nicu;

use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class FluidBalance extends Component
{
    #[Locked]
    public $no_rawat;
    public $selectedDate;

    // Properti untuk diisi oleh logika
    public ?MonitoringCycle $currentCycle = null;
    public ?float $previousBalance24h = null;
    public array $balancePer3Hours = [];

    // Properti untuk Total 24 Jam
    public $daily_iwl;
    public $totalIntake24h = 0;
    public $totalOutput24h = 0;
    public $totalUrine24h = 0;
    public $balance24h = 0;
    public bool $isReadOnly = false;
    /**
     * Terima properti dari parent
     */
    public function mount($no_rawat, $selectedDate, $isReadOnly)
    {
        $this->no_rawat = $no_rawat;
        $this->selectedDate = $selectedDate;
        $this->isReadOnly = $isReadOnly;
        $this->loadBalanceData();
    }

    /**
     * [LISTENER 1] Dipanggil oleh parent saat GANTI TANGGAL.
     */
    #[On('date-changed')]
    public function refreshData($selectedDate = null)
    {
        if ($selectedDate && is_string($selectedDate)) {
            $this->selectedDate = $selectedDate;
            $this->isReadOnly = !$this->isTodaySheet($this->selectedDate);
        }
        $this->loadBalanceData();
    }
    /**
     * Helper baru untuk mengecek apakah ini sheet hari ini.
     */
    private function isTodaySheet($dateString): bool
    {
        $baseTime = Carbon::parse($dateString);

        // Tentukan "sheet date" untuk tanggal yang diberikan
        $sheetDate = $baseTime->copy()->startOfDay();
        if ($baseTime->hour < 6) {
            $sheetDate->subDay();
        }
        $sheetDateStr = $sheetDate->format('Y-m-d');

        // Tentukan "sheet date" untuk HARI INI
        $todaySheetDate = now()->startOfDay();
        if (now()->hour < 6) {
            $todaySheetDate->subDay();
        }
        $todaySheetDateStr = $todaySheetDate->format('Y-m-d');

        return $sheetDateStr === $todaySheetDateStr;
    }
    /**
     * [LISTENER 2] Dipanggil oleh parent saat SIMPAN DATA.
     */
    #[On('record-saved')]
    public function refreshTable()
    {
        $this->loadBalanceData();
    }

    /**
     * Fungsi utama untuk mengambil dan menghitung data balance.
     */
    public function loadBalanceData()
    {
        // 1. Tentukan Tanggal
        $currentSheetDate = Carbon::parse($this->selectedDate);
        $previousSheetDate = $currentSheetDate->copy()->subDay()->format('Y-m-d');

        // 2. Cari Siklus Sebelumnya
        $previousCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $previousSheetDate)
            ->first();

        // 3. Kalkulasi Balance 24 Jam Sebelumnya
        if ($previousCycle && is_null($previousCycle->calculated_balance_24h)) {
            $this->calculateAndSaveBalance($previousCycle);
            $previousCycle->refresh();
        }
        $this->previousBalance24h = $previousCycle ? $previousCycle->calculated_balance_24h : null;

        // 4. Cari Siklus Saat Ini
        $this->currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $currentSheetDate->format('Y-m-d'))
            ->first();

        // 5. Muat Data & Kalkulasi
        if ($this->currentCycle) {
            $this->daily_iwl = $this->currentCycle->daily_iwl; // Muat IWL

            $allCycleRecords = MonitoringRecord::with('parenteralIntakes', 'enteralIntakes')
                ->where('monitoring_cycle_id', $this->currentCycle->id)
                ->get();

            // === (A) LOGIKA 3 JAM (Dari kode Anda) ===
            $this->balancePer3Hours = [];
            $cycleStart = Carbon::parse($this->currentCycle->start_time);

            for ($i = 0; $i < 8; $i++) {
                $blockStart = $cycleStart->copy()->addHours($i * 3);
                $blockEnd = $blockStart->copy()->addHours(3)->subSecond();
                $recordsInBlock = $allCycleRecords->whereBetween('record_time', [$blockStart, $blockEnd]);

                $totalMasuk = $recordsInBlock->sum(
                    fn($r) =>
                    ($r->intake_ogt ?? 0) +
                    ($r->intake_oral ?? 0) +
                    $r->parenteralIntakes->sum('volume') +
                    $r->enteralIntakes->sum('volume')
                );

                $totalKeluar = $recordsInBlock->sum(
                    fn($r) =>
                    ($r->output_urine ?? 0) +
                    ($r->output_bab ?? 0) +
                    ($r->output_residu ?? 0) +
                    ($r->output_ngt ?? 0) +
                    ($r->output_drain ?? 0)
                );

                $this->balancePer3Hours[] = [
                    'label' => $blockStart->format('H:i') . ' - ' . $blockEnd->format('H:i'),
                    'masuk' => $totalMasuk,
                    'keluar' => $totalKeluar,
                    'balance' => $totalMasuk - $totalKeluar,
                ];
            }

            // === (B) LOGIKA 24 JAM (Dipindah dari PatientMonitor) ===
            $this->totalIntake24h = $allCycleRecords->sum(fn($r) => ($r->intake_ogt ?? 0) + ($r->intake_oral ?? 0) + $r->parenteralIntakes->sum('volume') + $r->enteralIntakes->sum('volume'));
            $this->totalOutput24h = $allCycleRecords->sum(fn($r) => ($r->output_urine ?? 0) + ($r->output_bab ?? 0) + ($r->output_residu ?? 0) + ($r->output_ngt ?? 0) + ($r->output_drain ?? 0));
            $this->totalUrine24h = $allCycleRecords->sum('output_urine');
            $this->balance24h = $this->totalIntake24h - $this->totalOutput24h - ($this->daily_iwl ?? 0);

        } else {
            // Jika tidak ada siklus, reset datanya
            $this->reset(['balancePer3Hours', 'daily_iwl', 'totalIntake24h', 'totalOutput24h', 'totalUrine24h', 'balance24h', 'currentCycle']);
        }
    }

    /**
     * Simpan IWL harian
     */
    public function saveDailyIwl()
    {
        if ($this->currentCycle) {
            $this->validate(['daily_iwl' => 'nullable|numeric']);

            $this->currentCycle->daily_iwl = $this->daily_iwl ?: null;

            // Hitung ulang balance24h dengan IWL baru
            $this->balance24h = $this->totalIntake24h - $this->totalOutput24h - ($this->daily_iwl ?? 0);

            $this->currentCycle->calculated_balance_24h = $this->balance24h;
            $this->currentCycle->save();

            // Kirim notifikasi global
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Estimasi IWL Harian berhasil disimpan!']);

            // Refresh komponen lain (jika perlu)
            $this->dispatch('record-saved');
        } else {
            $this->dispatch('show-toast', ['type' => 'danger', 'message' => 'Siklus belum aktif.']);
        }
    }

    /**
     * Fungsi helper untuk menghitung balance siklus sebelumnya.
     */
    private function calculateAndSaveBalance(MonitoringCycle $cycle)
    {
        $records = MonitoringRecord::with('parenteralIntakes', 'enteralIntakes')
            ->where('monitoring_cycle_id', $cycle->id)
            ->get();

        $totalIntake = $records->sum(fn($r) => ($r->intake_ogt ?? 0) + ($r->intake_oral ?? 0) + $r->parenteralIntakes->sum('volume') + $r->enteralIntakes->sum('volume'));
        $totalOutput = $records->sum(fn($r) => ($r->output_urine ?? 0) + ($r->output_bab ?? 0) + ($r->output_residu ?? 0) + ($r->output_ngt ?? 0) + ($r->output_drain ?? 0));
        $iwl = $cycle->daily_iwl ?? 0;

        $cycle->calculated_balance_24h = $totalIntake - $totalOutput - $iwl;
        $cycle->save();
    }

    /**
     * Tampilkan skeleton loading (karena kita pakai 'lazy').
     */
    public function placeholder()
    {
        return <<<'HTML'
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden animate-pulse">
                <div class="p-6">
                    <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-3 pb-3"></div>
                    <div class="mt-4 space-y-2">
                        <div class="h-8 bg-gray-100 dark:bg-gray-700 rounded w-full"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-600 rounded w-full"></div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden animate-pulse">
                <div class="p-6">
                    <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-3 pb-3"></div>
                    <div class="mt-4 grid grid-cols-3 gap-6">
                        <div class="h-24 bg-gray-100 dark:bg-gray-700 rounded w-full"></div>
                        <div class="h-24 bg-gray-200 dark:bg-gray-600 rounded w-full"></div>
                        <div class="h-24 bg-gray-100 dark:bg-gray-700 rounded w-full"></div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.nicu.fluid-balance');
    }
}
