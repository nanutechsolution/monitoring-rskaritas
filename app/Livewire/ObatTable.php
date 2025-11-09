<?php

namespace App\Livewire;

use App\Models\Medication;
use App\Models\MonitoringCycle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;

class ObatTable extends Component
{
    public ?int $cycleId = null;
    public ?MonitoringCycle $currentCycle = null;
    public Collection $medications;

    // Properti untuk Modal (dipindah dari PatientMonitor)
    // Gunakan camelCase agar konsisten
    public $medicationName, $medicationDose, $medicationRoute, $medicationGivenAt;

    // Properti untuk status read-only
    public bool $isReadOnly = false;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->medications = new Collection();
        // Set default waktu untuk modal
        $this->given_at = now()->format('Y-m-d\TH:i');
    }

    /**
     * [LISTENER 1] Dipanggil oleh parent saat GANTI TANGGAL atau PINDAH TAB.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;

        // Kita juga perlu tahu siklusnya (untuk cek read-only)
        if ($this->cycleId) {
            $this->currentCycle = MonitoringCycle::find($this->cycleId);
            $this->isReadOnly = $this->checkIsReadOnly();
        } else {
            $this->currentCycle = null;
            $this->isReadOnly = true; // Jika tidak ada siklus, kunci form
        }

        $this->loadMedications();
    }

    /**
     * [LISTENER 2] Dipanggil saat SIMPAN DATA atau REFRESH.
     */
    #[On('refresh-medications')]
    public function refreshTable()
    {
        $this->loadMedications();
    }

    /**
     * Muat data obat
     */
    public function loadMedications()
    {
        if (!$this->cycleId) {
            $this->medications = new Collection();
            return;
        }

        $this->medications = Medication::with('pegawai')
            ->where('monitoring_cycle_id', $this->cycleId)
            ->orderBy('given_at', 'desc')
            ->get();
    }

    /**
     * FUNGSI BARU (Dipindah dari PatientMonitor)
     * Untuk menyimpan obat baru dari modal.
     */
    public function saveMedication()
    {
        if ($this->isReadOnly || !$this->currentCycle) {
            $this->dispatch('show-toast', ['type' => 'danger', 'message' => 'Tidak bisa menyimpan data pada siklus yang dikunci.']);
            return;
        }

        $validated = Validator::make(
            // Validasi properti lokal
            [
                'medication_name' => $this->medicationName,
                'dose' => $this->medicationDose,
                'route' => $this->medicationRoute,
                'given_at' => $this->medicationGivenAt,
            ],
            // Aturan
            [
                'medication_name' => 'required|string|max:255',
                'dose' => 'required|string|max:100',
                'route' => 'required|string|max:100',
                'given_at' => 'required|date_format:Y-m-d\TH:i',
            ]
        )->validate();

        try {
            Medication::create([
                'monitoring_cycle_id' => $this->currentCycle->id,
                'id_user' => auth()->id(),
                'medication_name' => $validated['medication_name'],
                'dose' => $validated['dose'],
                'route' => $validated['route'],
                'given_at' => $validated['given_at'],
            ]);

            $this->dispatch('refresh-medications'); // Refresh tabel ini
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Pemberian obat berhasil dicatat!']);
            $this->dispatch('close-medication-modal'); // Kirim event ke Alpine untuk tutup modal
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'danger', 'message' => 'Gagal menyimpan obat: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper untuk reset form modal
     */
    public function resetForm()
    {
        // Reset properti
        $this->reset(['medicationName', 'medicationDose', 'medicationRoute']);
        // Set default waktu ke 'now' setiap kali reset
        $this->medicationGivenAt = now()->format('Y-m-d\TH:i');
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
        // Arahkan ke file partial Blade Anda yang sudah ada
        return view('livewire.patient-monitor.partials.output-tabel-obat-nicu', [
            // Kirim data yang dibutuhkan oleh @props di Blade
            'title' => 'Riwayat Pemberian Obat',
            'medications' => $this->medications,
            // Kirim recentMedicationNames jika Anda memindahkannya ke sini
            'recentMedicationNames' => $this->currentCycle ? Medication::where('monitoring_cycle_id', $this->currentCycle->id)->distinct()->pluck('medication_name') : collect()
        ]);
    }
}
