<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ObservationTable extends Component
{
    public MonitoringCycleIcu $cycle;
    // Jika Anda ingin filter shift di tabel ini juga, tambahkan:
    // public string $filterShift = 'all';

    public function mount(MonitoringCycleIcu $cycle)
    {
        $this->cycle = $cycle;
    }

    /**
     * [Computed Property] - Dipindahkan dari ObservationGrid
     * Mengambil semua record inputan real-time (sesuai filter jika ada).
     */
    #[Computed(persist: true)]
    public function allRecords(): Collection
    {
        // Jika Anda tambahkan filterShift, logic filter masuk ke sini
        // $shifts = [...];

        return $this->cycle->records()
            ->with('inputter:nik,nama')
            ->orderBy('observation_time', 'asc')
            // ->when($this->filterShift != 'all', function ($query) use ($shifts) { ... })
            ->get();
    }

    /**
     * Helper: Daftar Parameter (Baris) - Dipindahkan dari ObservationGrid
     */
    public function parameters(): array
    {
        return [
            ['key' => 'suhu', 'label' => 'Suhu (°C)', 'group' => 'HEMODINAMIK'],
            ['key' => 'nadi', 'label' => 'Nadi (x/mnt)', 'group' => 'HEMODINAMIK'],
            ['key' => 'tensi', 'label' => 'Tensi', 'group' => 'HEMODINAMIK'],
            ['key' => 'map', 'label' => 'MAP', 'group' => 'HEMODINAMIK'],
            ['key' => 'irama_ekg', 'label' => 'Irama EKG', 'group' => 'HEMODINAMIK'],

            ['key' => 'rr', 'label' => 'RR (x/mnt)', 'group' => 'RESPIRASI'],
            ['key' => 'spo2', 'label' => 'SpO2 (%)', 'group' => 'RESPIRASI'],
            ['key' => 'cvp', 'label' => 'CVP', 'group' => 'RESPIRASI'],
            ['key' => 'cuff_pressure', 'label' => 'Cuff Pressure', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_mode', 'label' => 'Mode Ventilator', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_f', 'label' => 'Vent. F (Freq)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_tv', 'label' => 'Vent. TV (Vol)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_fio2', 'label' => 'Vent. FiO2 (%)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_peep', 'label' => 'Vent. PEEP', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_pinsp', 'label' => 'Vent. P Insp', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_ie_ratio', 'label' => 'Vent. I:E Ratio', 'group' => 'RESPIRASI'],

            ['key' => 'gcs', 'label' => 'GCS', 'group' => 'OBSERVASI'],
            ['key' => 'pupil', 'label' => 'Pupil (Ki/Ka)', 'group' => 'OBSERVASI'],
            ['key' => 'kesadaran', 'label' => 'Kesadaran', 'group' => 'OBSERVASI'],
            ['key' => 'nyeri', 'label' => 'Nyeri (0-10)', 'group' => 'OBSERVASI'],
            ['key' => 'fall_risk_assessment', 'label' => 'Risiko Jatuh', 'group' => 'OBSERVASI'],

            ['key' => 'cairan_masuk', 'label' => 'Cairan Masuk', 'group' => 'CAIRAN'],
            ['key' => 'cairan_keluar', 'label' => 'Cairan Keluar', 'group' => 'CAIRAN'],

            ['key' => 'clinical_note', 'label' => 'Catatan Klinis/Masalah', 'group' => 'CATATAN'],
            ['key' => 'medication_administration', 'label' => 'Tindakan/Obat', 'group' => 'CATATAN'],
        ];
    }
    /**
     * [Computed Property BARU]
     * Menghitung total cairan masuk & keluar PER JAM untuk tabel ringkasan.
     * Menggunakan data dari allRecords agar filter shift tetap berlaku.
     */
    #[Computed(persist: true)]
    public function hourlyFluidSummary(): array
    {
        // 1. Siapkan 24 "slot" jam, dari 00 s/d 23
        $hourlySlots = [];
        for ($i = 0; $i < 24; $i++) {
            $hourString = str_pad($i, 2, '0', STR_PAD_LEFT);
            $hourlySlots[$hourString] = [
                'hour' => $hourString,
                'masuk' => 0,
                'keluar' => 0,
            ];
        }

        // 2. Kelompokkan record berdasarkan jam (dari data yg sudah difilter shift)
        $recordsByHour = $this->allRecords->groupBy(function ($record) {
            return $record->observation_time->format('H'); // 'H' = 00 s/d 23
        });

        // 3. Isi slot dengan SUM cairan per jam
        foreach ($recordsByHour as $hour => $hourRecords) {
            $hourlySlots[$hour]['masuk'] = $hourRecords->sum('cairan_masuk_volume');
            $hourlySlots[$hour]['keluar'] = $hourRecords->sum('cairan_keluar_volume');
        }

        return $hourlySlots;
    }
    public function render()
    {
        return view('livewire.icu.observation-table', [
            'allParameters' => $this->parameters()
        ])->layout('layouts.app');
    }
}
