<?php

namespace App\Livewire;

use App\Models\PippAssessment;
use App\Models\MonitoringCycle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;

class PippTable extends Component
{
    public ?int $cycleId = null;
    public ?MonitoringCycle $currentCycle = null;
    public Collection $pippAssessments;

    // Properti BARU untuk status read-only
    public bool $isReadOnly = false;

    // Properti BARU untuk modal PIPP
    public $pipp_assessment_time;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->pippAssessments = new Collection();
    }

    /**
     * [LISTENER 1] Dipanggil oleh parent saat GANTI TANGGAL atau PINDAH TAB.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;

        if ($this->cycleId) {
            $this->currentCycle = MonitoringCycle::find($this->cycleId);
            $this->isReadOnly = $this->checkIsReadOnly();
        } else {
            $this->currentCycle = null;
            $this->isReadOnly = true;
        }

        $this->loadPippAssessments();
    }

    /**
     * [LISTENER 2] Dipanggil saat SIMPAN DATA atau REFRESH.
     */
    #[On('record-saved')]
    #[On('refresh-pip')]
    public function refreshTable()
    {
        $this->loadPippAssessments();
    }

    /**
     * Muat data PIPP
     */
    public function loadPippAssessments()
    {
        if (!$this->cycleId) {
            $this->pippAssessments = new Collection();
            return;
        }

        $this->pippAssessments = PippAssessment::with('pegawai')
            ->where('monitoring_cycle_id', $this->cycleId)
            ->orderBy('assessment_time', 'desc')
            ->get();
    }

    /**
     * FUNGSI BARU (Dipindah dari PatientMonitor)
     * Untuk menyimpan PIPP baru dari modal.
     * Menerima $data dari Alpine.js
     */
    public function savePippScore($data)
    {
        if ($this->isReadOnly || !$this->currentCycle) {
            $this->dispatch('show-toast', ['type' => 'danger', 'message' => 'Tidak bisa menyimpan data pada siklus yang dikunci.']);
            return false; // Gagal
        }

        $validatedData = Validator::make($data, [
            'gestational_age' => 'required|numeric',
            'behavioral_state' => 'required|numeric',
            'max_heart_rate' => 'required|numeric',
            'min_oxygen_saturation' => 'required|numeric',
            'brow_bulge' => 'required|numeric',
            'eye_squeeze' => 'required|numeric',
            'nasolabial_furrow' => 'required|numeric',
            'total_score' => 'required|numeric',
        ])->validate();

        try {
            PippAssessment::create([
                'monitoring_cycle_id' => $this->currentCycle->id,
                'id_user' => auth()->id(),
                'assessment_time' => $this->pipp_assessment_time ?? now(),
                'gestational_age' => $validatedData['gestational_age'],
                'behavioral_state' => $validatedData['behavioral_state'],
                'max_heart_rate' => $validatedData['max_heart_rate'],
                'min_oxygen_saturation' => $validatedData['min_oxygen_saturation'],
                'brow_bulge' => $validatedData['brow_bulge'],
                'eye_squeeze' => $validatedData['eye_squeeze'],
                'nasolabial_furrow' => $validatedData['nasolabial_furrow'],
                'total_score' => $validatedData['total_score'],
            ]);

            $this->dispatch('refresh-pip'); // Refresh tabel ini
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Penilaian Nyeri (PIPP) berhasil dicatat!']);
            return true; // Sukses

        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'danger', 'message' => 'Gagal menyimpan PIPP: ' . $e->getMessage()]);
            return false; // Gagal
        }
    }

    /**
     * Helper untuk set waktu PIPP saat modal dibuka
     * Dipanggil oleh Alpine @click
     */
    public function setPippTime()
    {
        $this->pipp_assessment_time = now();
    }

    /**
     * Helper untuk mengecek status read-only
     */
    private function checkIsReadOnly(): bool
    {
        if (!$this->currentCycle) return true;
        $targetDate = \Carbon\Carbon::parse($this->currentCycle->sheet_date);
        $todaySheetDate = now()->startOfDay();
        if (now()->hour < 6) {
            $todaySheetDate->subDay();
        }
        return !$targetDate->isSameDay($todaySheetDate);
    }

    public function render()
    {
        return view('livewire.patient-monitor.partials.output-tabel-pipp');
    }
}
