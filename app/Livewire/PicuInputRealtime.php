<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuCycle;
use App\Models\PicuMonitoring;
use Livewire\Attributes\On;

class PicuInputRealtime extends Component
{
    public $monitoringSheetId;
    public $jamGridSaatIni;

    // public $currentCycle; // <-- HAPUS INI. Ini penyebab masalah.

    // --- Public Properties untuk Form Binding (Tetap Sama) ---
    public $temp_inkubator;
    public $temp_skin;
    public $heart_rate;
    public $respiratory_rate;
    public $tekanan_darah;
    public $sat_o2;
    public $irama_ekg;
    public $skala_nyeri;
    public $huidifier_inkubator;
    public $cyanosis;
    public $pucat;
    public $icterus;
    public $crt_lt_2;
    public $bradikardia;
    public $stimulasi;



    public $vent_mode; // Ini untuk radio button (Nasal, CPAP, HFO, Mekanik)

    // Nasal
    public $vent_fio2_nasal;
    public $vent_flow_nasal;

    // CPAP
    public $vent_fio2_cpap;
    public $vent_flow_cpap;
    public $vent_peep_cpap;

    // HFO
    public $vent_fio2_hfo;
    public $vent_frekuensi_hfo;
    public $vent_map_hfo;
    public $vent_amplitudo_hfo;
    public $vent_it_hfo;

    // Mekanik (BLLOD GAS MONITOR)
    public $vent_mode_mekanik;
    public $vent_fio2_mekanik;
    public $vent_peep_mekanik;
    public $vent_pip_mekanik;
    public $vent_tv_vte_mekanik;
    public $vent_rr_spontan_mekanik;
    public $vent_p_max_mekanik;
    public $vent_ie_mekanik;
    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;
        $this->loadCurrentCycle();
    }

    #[On('refresh-input-realtime')]
    public function loadCurrentCycle()
    {
        $now = now();
        $this->jamGridSaatIni = $now->hour;

        // Cari data cycle HANYA untuk diisi ke form
        // Kita gunakan variabel $currentCycle lokal, bukan public property
        $currentCycle = PicuCycle::firstOrNew([
            'picu_monitoring_id' => $this->monitoringSheetId,
            'jam_grid' => $this->jamGridSaatIni,
        ]);

        // Isi public properties dari data yang ada
        $this->fill($currentCycle->toArray());

        // Pastikan boolean di-set dengan benar
        $this->cyanosis = (bool) $currentCycle->cyanosis;
        $this->pucat = (bool) $currentCycle->pucat;
        $this->icterus = (bool) $currentCycle->icterus;
        $this->crt_lt_2 = (bool) $currentCycle->crt_lt_2;
        $this->bradikardia = (bool) $currentCycle->bradikardia;
        $this->stimulasi = (bool) $currentCycle->stimulasi;
    }

    /**
     * =============================================
     * INI ADALAH FUNGSI save() YANG SUDAH DIPERBAIKI
     * =============================================
     */
    public function save()
    {
        // 1. Validasi semua data dari public property
        $validatedData = $this->validate([
            'temp_inkubator' => 'nullable|numeric',
            'temp_skin' => 'nullable|numeric|between:30,45',
            'heart_rate' => 'nullable|integer|max:300',
            'respiratory_rate' => 'nullable|integer',
            'tekanan_darah' => 'nullable|string|max:10',
            'sat_o2' => 'nullable|integer',
            'irama_ekg' => 'nullable|string|max:50',
            'skala_nyeri' => 'nullable|integer',
            'huidifier_inkubator' => 'nullable|string|max:50',
            'cyanosis' => 'nullable|boolean',
            'pucat' => 'nullable|boolean',
            'icterus' => 'nullable|boolean',
            'crt_lt_2' => 'nullable|boolean',
            'bradikardia' => 'nullable|boolean',
            'stimulasi' => 'nullable|boolean',
            'vent_mode' => 'nullable|string|max:50',
            'vent_fio2_nasal' => 'nullable|numeric',
            'vent_flow_nasal' => 'nullable|numeric',
            'vent_fio2_cpap' => 'nullable|numeric',
            'vent_flow_cpap' => 'nullable|numeric',
            'vent_peep_cpap' => 'nullable|numeric',
            'vent_fio2_hfo' => 'nullable|numeric',
            'vent_frekuensi_hfo' => 'nullable|integer',
            'vent_map_hfo' => 'nullable|numeric',
            'vent_amplitudo_hfo' => 'nullable|integer',
            'vent_it_hfo' => 'nullable|string|max:10',
            'vent_mode_mekanik' => 'nullable|string|max:50',
            'vent_fio2_mekanik' => 'nullable|numeric',
            'vent_peep_mekanik' => 'nullable|numeric',
            'vent_pip_mekanik' => 'nullable|numeric',
            'vent_tv_vte_mekanik' => 'nullable|string|max:20',
            'vent_rr_spontan_mekanik' => 'nullable|string|max:20',
            'vent_p_max_mekanik' => 'nullable|numeric',
            'vent_ie_mekanik' => 'nullable|string|max:10',
        ]);

        // 2. Gabungkan data sistem (petugas, waktu)
        $dataToSave = array_merge($validatedData, [
            'waktu_observasi' => now(),
            'petugas_id' => auth()->user()->id_user, // Pastikan ini benar
        ]);

        // 3. Gunakan updateOrCreate()
        // Ini adalah cara yang aman & benar di Livewire.
        // Dia akan mencari baris berdasarkan 'picu_monitoring_id' dan 'jam_grid',
        // lalu meng-update-nya dengan $dataToSave (jika ada),
        // atau membuat baris baru (jika belum ada).
        PicuCycle::updateOrCreate(
            [
                // Kunci untuk mencari (ini yang hilang dari SQL Anda)
                'picu_monitoring_id' => $this->monitoringSheetId,
                'jam_grid' => $this->jamGridSaatIni,
            ],
            $dataToSave // Data untuk di-insert atau di-update
        );

        // 4. Kirim notifikasi
        session()->flash('success', 'Data observasi jam ' . $this->jamGridSaatIni . ' berhasil disimpan.');
        $this->dispatch('cycle-updated'); // Emit event agar grid review update
    }

    public function render()
    {
        // Tampilan akan berganti otomatis jika jam berganti
        if (now()->hour != $this->jamGridSaatIni) {
            $this->loadCurrentCycle();
        }

        return view('livewire.picu-input-realtime')->layout('layouts.app');
    }
}
