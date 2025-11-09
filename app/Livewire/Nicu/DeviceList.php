<?php

namespace App\Livewire\Nicu;

use App\Models\MonitoringCycle;
use App\Models\PatientDevice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class DeviceList extends Component
{
    #[Locked]
    public $no_rawat;
    public $selectedDate;

    public ?MonitoringCycle $currentCycle = null;
    public Collection $patientDevices;

    public function mount($no_rawat, $selectedDate)
    {

        $this->no_rawat = $no_rawat;
        $this->selectedDate = $selectedDate;
        $this->patientDevices = new Collection();
        $this->loadCycleAndDevices();
    }

    /**
     * [LISTENER] Dengar event dari parent.
     */
    #[On('record-saved')]
    #[On('date-changed')]
    #[On('refresh-devices')]
    public function refreshData($selectedDate = null)
    {
        if ($selectedDate && strtotime($selectedDate)) {
            // Hanya ubah kalau benar-benar tanggal valid
            $this->selectedDate = $selectedDate;
        }

        $this->loadCycleAndDevices();
    }


    public function loadCycleAndDevices()
    {
        // 1. Tentukan Tanggal Sheet
        // $this->selectedDate SUDAH BENAR. Jangan cek jamnya.
        $targetDateString = Carbon::parse($this->selectedDate)->format('Y-m-d');

        // 2. Cari Siklus
        $this->currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $targetDateString)
            ->first();
        // 3. Muat Alat jika siklus ada
        if ($this->currentCycle) {
            $cycleStart = $this->currentCycle->start_time;
            $cycleEnd = $this->currentCycle->end_time;

            $this->patientDevices = PatientDevice::with(['installer', 'remover'])
                ->where('no_rawat', $this->no_rawat)
                ->where('installation_date', '<=', $cycleEnd)
                ->where(function ($query) use ($cycleStart) {
                    $query->whereNull('removal_date')
                        ->orWhere('removal_date', '>=', $cycleStart);
                })
                ->orderBy('installation_date')
                ->get();
        } else {
            $this->patientDevices = new Collection();
        }
    }

    /**
     * (Logika dipindah dari PatientMonitor)
     */
    public function saveDevice($data)
    {
        $validated = Validator::make($data, [
            'device_name' => 'required|string|max:255',
            'size' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'installation_date' => 'required|date_format:Y-m-d\TH:i',
        ])->validate();

        try {
            PatientDevice::create([
                'no_rawat' => $this->no_rawat,
                'device_name' => $validated['device_name'],
                'size' => $validated['size'],
                'location' => $validated['location'],
                'installation_date' => $validated['installation_date'],
                'installed_by_user_id' => auth()->id(),
            ]);

            $this->dispatch('refresh-devices'); // Refresh komponen ini
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Alat baru berhasil ditambahkan!']);
            return true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['type' => 'danger', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
            return false;
        }
    }

    /**
     * (Logika dipindah dari PatientMonitor)
     */
    public function confirmRemoveDevice($deviceId)
    {
        if (!$deviceId)
            return;

        $device = PatientDevice::find($deviceId);
        if ($device && $device->no_rawat === $this->no_rawat) {
            $device->removal_date = now();
            $device->removed_by_user_id = auth()->id();
            $device->save();

            $this->dispatch('refresh-devices'); // Refresh komponen ini
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Alat berhasil dilepas!']);
        }
    }

    public function render()
    {
        return view('livewire.nicu.device-list');
    }
}
