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

    public string $filterDuration = 'block_6hr';

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

        return $this->cycle->records()
            ->with('inputter:nik,nama')
            ->orderBy('observation_time', 'asc')
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
            $mergedData['irama_ekg'] = $recordsInMinute->last(fn($r) => $r->irama_ekg !== null)?->irama_ekg;
            $mergedData['kesadaran'] = $recordsInMinute->last(fn($r) => $r->kesadaran !== null)?->kesadaran;
            $mergedData['nyeri'] = $recordsInMinute->last(fn($r) => $r->nyeri !== null)?->nyeri;
            $mergedData['cuff_pressure'] = $recordsInMinute->last(fn($r) => $r->cuff_pressure !== null)?->cuff_pressure;
            $mergedData['fall_risk_assessment'] = $recordsInMinute->last(fn($r) => $r->fall_risk_assessment !== null)?->fall_risk_assessment;
            $mergedData['ventilator_mode'] = $recordsInMinute->last(fn($r) => $r->ventilator_mode !== null)?->ventilator_mode;
            $mergedData['ventilator_f'] = $recordsInMinute->last(fn($r) => $r->ventilator_f !== null)?->ventilator_f;
            $mergedData['ventilator_tv'] = $recordsInMinute->last(fn($r) => $r->ventilator_tv !== null)?->ventilator_tv;
            $mergedData['ventilator_fio2'] = $recordsInMinute->last(fn($r) => $r->ventilator_fio2 !== null)?->ventilator_fio2;
            $mergedData['ventilator_peep'] = $recordsInMinute->last(fn($r) => $r->ventilator_peep !== null)?->ventilator_peep;
            $mergedData['ventilator_pinsp'] = $recordsInMinute->last(fn($r) => $r->ventilator_pinsp !== null)?->ventilator_pinsp;
            $mergedData['ventilator_ie_ratio'] = $recordsInMinute->last(fn($r) => $r->ventilator_ie_ratio !== null)?->ventilator_ie_ratio;

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
     * [BARU] Menghasilkan array jam dalam urutan klinis (06, 07, ..., 05)
     */
    #[Computed]
    public function clinicalHours(): array
    {
        $hours = [];
        // Mulai dari jam 6
        for ($i = 6; $i < 24; $i++) {
            $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        // Lanjutkan dengan jam 0 hingga 5 (hari berikutnya)
        for ($i = 0; $i < 6; $i++) {
            $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        return $hours;
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


    /**
     * Menghitung ringkasan balance berdasarkan filter durasi (1, 3, atau 6 jam).
     */
    #[Computed]
    public function hourlyBalanceSummary(): array
    {
        $summary = collect($this->hourlyFluidSummary);
        $duration = $this->filterDuration;
        $balances = [];

        // [UBAH] Menggunakan urutan jam klinis yang baru
        $hours = $this->clinicalHours;

        // Loop melalui setiap jam klinis untuk menghitung akumulasi
        foreach ($hours as $currentHourStr) {
            $currentHour = (int) $currentHourStr;
            $masuk = 0;
            $keluar = 0;

            // Hitung akumulasi untuk periode $duration jam terakhir
            for ($i = 0; $i < $duration; $i++) {
                $checkHour = $currentHour - $i;

                // Tangani rollover (misal: jam 06 - 1 = jam 05 hari sebelumnya, tapi dalam siklus ini, jam 06 adalah awal)
                // Kita perlu memetakan kembali jam yang mundur ke jam di rentang 00-23.
                // Logika: Jika currentHour adalah 06, dan duration 6, kita cek jam 06, 05, 04, 03, 02, 01.

                $checkHour = ($checkHour < 0) ? 24 + $checkHour : $checkHour;

                $checkHourStr = str_pad($checkHour, 2, '0', STR_PAD_LEFT);

                if (isset($summary[$checkHourStr])) {
                    $masuk += $summary[$checkHourStr]['masuk'];
                    $keluar += $summary[$checkHourStr]['keluar'];
                }
            }

            $balances[$currentHourStr] = [
                'hour' => $currentHourStr,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'balance' => $masuk - $keluar,
            ];
        }
        return collect($balances)->sortBy(fn($item, $key) => array_search($key, $this->clinicalHours))->all();
    }

    /**
     * [UBAH TOTAL] Menghitung Ringkasan Balance dalam Blok Waktu Tetap.
     */
    #[Computed]
    public function fixedBlockSummary(): array
    {
        $hourlySummary = collect($this->hourlyFluidSummary);
        $duration = $this->filterDuration;
        $blocks = [];

        // 1. Tentukan Blok Waktu Berdasarkan Filter
        $blockDefinitions = [];

        if ($duration === 'block_shift') {
            // Blok Shift (Pagi: 06-13, Sore: 14-20, Malam: 21-05)
            $blockDefinitions = [
                'PAGI (06-13)' => range(6, 13), // 8 jam
                'SORE (14-20)' => range(14, 20), // 7 jam
                'MALAM (21-05)' => array_merge(range(21, 23), range(0, 5)), // 9 jam
            ];
        } elseif ($duration === 'block_6hr') {
            // Blok 6 Jam Klinis (06-11, 12-17, 18-23, 00-05)
            $blockDefinitions = [
                '06-11' => range(6, 11),
                '12-17' => range(12, 17),
                '18-23' => range(18, 23),
                '00-05' => range(0, 5),
            ];
        } else {
            // Blok Waktu Standar (1 atau 3 jam), dimulai dari 06
            $durationInt = (int) $duration;
            $clinicalHours = $this->clinicalHours();
            $totalHours = count($clinicalHours);
            $startIndex = 0;

            while ($startIndex < $totalHours) {
                $blockHours = [];
                for ($i = 0; $i < $durationInt; $i++) {
                    $index = ($startIndex + $i) % $totalHours;
                    $blockHours[] = (int) $clinicalHours[$index];
                }

                $blockStart = str_pad($blockHours[0], 2, '0', STR_PAD_LEFT);
                $blockEnd = str_pad(end($blockHours), 2, '0', STR_PAD_LEFT);

                if ($durationInt === 1) {
                    $label = $blockStart; // Jika 1 Jam, tampilkan jam saja
                } else {
                    $label = "{$blockStart}-{$blockEnd}"; // Cth: 06-08
                }

                $blockDefinitions[$label] = $blockHours;
                $startIndex += $durationInt;
            }
        }

        // 2. Hitung Total untuk Setiap Blok
        foreach ($blockDefinitions as $label => $hoursInBlock) {
            $masuk = 0;
            $keluar = 0;

            foreach ($hoursInBlock as $hour) {
                $hourStr = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $masuk += $hourlySummary[$hourStr]['masuk'] ?? 0;
                $keluar += $hourlySummary[$hourStr]['keluar'] ?? 0;
            }

            $blocks[$label] = [
                'label' => $label,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'balance' => $masuk - $keluar,
            ];
        }

        return $blocks;
    }
    public function render()
    {
        return view('livewire.icu.observation-table', [
            'allParameters' => $this->parameters(),
            'fluidSummary' => $this->fixedBlockSummary(),
        ])->layout('layouts.app');
    }
}
