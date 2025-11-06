<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Str;
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
     * Gabungkan data per menit (isi sel)
     */
    #[Computed(persist: true)]
    public function mergedRecordsPerMinute(): Collection
    {
        return $this->uniqueTimestamps->mapWithKeys(function ($timestamp) {
            $recordsInMinute = $this->recordsGroupedByMinute[$timestamp];
            $mergedData = [
                'observation_time' => $recordsInMinute->first()->observation_time,
                'inputters' => $recordsInMinute->pluck('inputter.nama')->filter()->unique()->implode(', '),
                'fluids_in' => [],
                'fluids_out' => [],
                'notes' => [],
                'meds' => [],
                // Inisialisasi semua properti lain sebagai null
                'suhu' => null,
                'nadi' => null,
                'tensi_sistol' => null,
                'tensi_diastol' => null,
                'map' => null,
                'rr' => null,
                'spo2' => null,
                'gcs_e' => null,
                'gcs_v' => null,
                'gcs_m' => null,
                'gcs_total' => null,
                'kesadaran' => null,
                'nyeri' => null,
                'cvp' => null,
                'cuff_pressure' => null,
                'fall_risk_assessment' => null,
                'irama_ekg' => null,
                'ventilator_mode' => null,
                'ventilator_f' => null,
                'ventilator_tv' => null,
                'ventilator_fio2' => null,
                'ventilator_peep' => null,
                'ventilator_ie_ratio' => null,
                'pupil_left_size_mm' => null,
                'pupil_left_reflex' => null,
                'pupil_right_size_mm' => null,
                'pupil_right_reflex' => null,
            ];

            // Ambil data TTV/Obs TERAKHIR
            // (Lengkapi semua field TTV/Obs di sini)
            $mergedData['suhu'] = $recordsInMinute->last(fn($r) => $r->suhu !== null)?->suhu;
            $mergedData['nadi'] = $recordsInMinute->last(fn($r) => $r->nadi !== null)?->nadi;
            $mergedData['tensi_sistol'] = $recordsInMinute->last(fn($r) => $r->tensi_sistol !== null)?->tensi_sistol;
            $mergedData['tensi_diastol'] = $recordsInMinute->last(fn($r) => $r->tensi_sistol !== null)?->tensi_diastol;
            $mergedData['map'] = $recordsInMinute->last(fn($r) => $r->map !== null)?->map;
            $mergedData['rr'] = $recordsInMinute->last(fn($r) => $r->rr !== null)?->rr;
            $mergedData['spo2'] = $recordsInMinute->last(fn($r) => $r->spo2 !== null)?->spo2;
            $mergedData['cvp'] = $recordsInMinute->last(fn($r) => $r->cvp !== null)?->cvp;
            $mergedData['gcs_e'] = $recordsInMinute->last(fn($r) => $r->gcs_e !== null)?->gcs_e;
            $mergedData['gcs_v'] = $recordsInMinute->last(fn($r) => $r->gcs_v !== null)?->gcs_v;
            $mergedData['gcs_m'] = $recordsInMinute->last(fn($r) => $r->gcs_m !== null)?->gcs_m;
            $mergedData['pupil_left_size_mm'] = $recordsInMinute->last(fn($r) => $r->pupil_left_size_mm !== null)?->pupil_left_size_mm;
            $mergedData['pupil_left_reflex'] = $recordsInMinute->last(fn($r) => $r->pupil_left_reflex !== null)?->pupil_left_reflex;
            $mergedData['pupil_right_size_mm'] = $recordsInMinute->last(fn($r) => $r->pupil_right_size_mm !== null)?->pupil_right_size_mm;
            $mergedData['pupil_right_reflex'] = $recordsInMinute->last(fn($r) => $r->pupil_right_reflex !== null)?->pupil_right_reflex;
            // ... (Tambahkan semua field TTV/Obs lain: vent, nyeri, kesadaran, dll.)

            // Kumpulkan data (NORMALISASI NAMA CAIRAN DI SINI)
            foreach ($recordsInMinute as $record) {
                if ($record->cairan_masuk_volume) {
                    $mergedData['fluids_in'][] = ['jenis' => Str::title($record->cairan_masuk_jenis), 'volume' => $record->cairan_masuk_volume, 'is_parenteral' => $record->is_parenteral, 'is_enteral' => $record->is_enteral];
                }
                if ($record->cairan_keluar_volume) {
                    $mergedData['fluids_out'][] = ['jenis' => $record->cairan_keluar_jenis, 'volume' => $record->cairan_keluar_volume];
                }
                if ($record->clinical_note) {
                    $mergedData['notes'][] = $record->clinical_note;
                }
                if ($record->medication_administration) {
                    $mergedData['meds'][] = $record->medication_administration;
                }
            }
            $mergedData['clinical_note'] = implode("\n", $mergedData['notes']);
            $mergedData['medication_administration'] = implode("\n", $mergedData['meds']);

            return [$timestamp => (object) $mergedData];
        });
    }
    /**
     * Kelompokkan per menit (Sama seperti di PDF Controller)
     */
    #[Computed(persist: true)]
    public function recordsGroupedByMinute(): Collection
    {
        return $this->allRawRecords->groupBy(fn($record) => $record->observation_time->format('Y-m-d H:i'));
    }
    /**
     * Ambil semua data mentah (Sama seperti di PDF Controller)
     */
    #[Computed(persist: true)]
    public function allRawRecords(): Collection
    {
        return $this->cycle->records()
            ->with('inputter:nik,nama')
            ->orderBy('observation_time', 'asc')
            // ->when($this->filterShift != 'all', ...) // Filter bisa ditambahkan di sini
            ->get();
    }
    /**
     * Dapatkan daftar timestamp unik (header kolom)
     */
    #[Computed(persist: true)]
    public function uniqueTimestamps(): Collection
    {
        return $this->recordsGroupedByMinute->keys();
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
     * Ambil jenis cairan unik (DENGAN NORMALISASI)
     */
    #[Computed(persist: true)] public function uniqueParenteralFluids(): Collection
    {
        return $this->allRawRecords->where('is_parenteral', true)->whereNotNull('cairan_masuk_volume')->pluck('cairan_masuk_jenis')
            ->map(fn($name) => Str::title($name))->unique()->sort();
    }
    #[Computed(persist: true)] public function uniqueEnteralFluids(): Collection
    {
        return $this->allRawRecords->where('is_enteral', true)->whereNotNull('cairan_masuk_volume')->pluck('cairan_masuk_jenis')
            ->map(fn($name) => Str::title($name))->unique()->sort();
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
