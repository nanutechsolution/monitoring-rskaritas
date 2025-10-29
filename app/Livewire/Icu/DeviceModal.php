<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use App\Models\MonitoringDevice;
use Livewire\Component;
use Livewire\Attributes\Rule; // Import Rule attribute for validation

class DeviceModal extends Component
{
    public int $cycleId; // Menerima ID cycle dari parent
    public MonitoringCycleIcu $cycle; // Kita load cycle di sini

    // State untuk form
    #[Rule('required|in:ALAT,TUBE')]
    public string $device_category = '';

    #[Rule('required|string|max:100')]
    public string $device_name = '';

    #[Rule('nullable|string|max:20')]
    public string $ukuran = '';

    #[Rule('nullable|string|max:50')]
    public string $lokasi = '';

    #[Rule('nullable|date_format:Y-m-d')]
    public ?string $tanggal_pasang = null;

    // State tambahan untuk UI
    public array $filteredDeviceNames = [];
    public string $otherDeviceName = '';
    public bool $isOther = false;

    /**
     * Mount: Load cycle & reset form
     */
    public function mount(int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->cycle = MonitoringCycleIcu::findOrFail($cycleId);
        $this->resetForm(); // Panggil reset di awal
    }

    /**
     * Daftar Opsi Alat/Tube Standar
     */
    public function getDeviceOptions(): array
    {
        return [
            'ALAT' => ['IV Line', 'CVC', 'Arteri Line', 'Swanz Ganz', 'Lainnya (Alat)'],
            'TUBE' => ['NGT', 'Urin Kateter', 'WSD', 'Drain', 'Lainnya (Tube)'],
        ];
    }

    /**
     * Dipanggil saat Kategori berubah (updated hook)
     */
    public function updatedDeviceCategory($value)
    {
        $options = $this->getDeviceOptions();
        $this->filteredDeviceNames = $options[$value] ?? [];
        $this->device_name = ''; // Reset pilihan nama
        $this->isOther = false;
        $this->otherDeviceName = '';
    }

    /**
     * Dipanggil saat Nama Alat/Tube berubah
     */
    public function updatedDeviceName($value)
    {
        $this->isOther = str_starts_with($value, 'Lainnya');
        if (!$this->isOther) {
            $this->otherDeviceName = '';
        }
    }

    /**
     * Method Simpan
     */
    public function saveDevice()
    {
        // Validasi input "Lainnya" jika dipilih
        if ($this->isOther) {
            $this->validateOnly('otherDeviceName', ['otherDeviceName' => 'required|string|max:100']);
            $this->device_name = $this->otherDeviceName; // Gunakan nama dari input 'Lainnya'
        }

        // Validasi semua properti public yang punya attribute Rule
        $validatedData = $this->validate();

        // Tambah cycle ID dan simpan
        $validatedData['monitoring_cycle_icu_id'] = $this->cycleId;
        MonitoringDevice::create($validatedData);

        // Kirim event ke parent (Workspace)
        $this->dispatch('device-added');
        $this->dispatch('close-modal');
        $this->dispatch('notification-sent', ['message' => 'Alat/Tube berhasil ditambahkan.', 'type' => 'success']);

        // Reset form setelah simpan
        $this->resetForm();
    }

    /**
     * Method Reset Form
     */
    public function resetForm()
    {
        $this->reset(
            'device_category',
            'device_name',
            'ukuran',
            'lokasi',
            'tanggal_pasang',
            'filteredDeviceNames',
            'otherDeviceName',
            'isOther'
        );
    }


    public function render()
    {
        return view('livewire.icu.device-modal', [
             'deviceOptions' => $this->getDeviceOptions()
        ])->layout('layouts.app');
    }
}
