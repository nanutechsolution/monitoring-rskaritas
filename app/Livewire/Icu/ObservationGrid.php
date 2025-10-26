<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use App\Models\MonitoringRecordIcu;
use App\Models\RegPeriksa;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ObservationGrid extends Component
{
    public MonitoringCycleIcu $cycle;
    public string $filterShift = 'all';
    public array $staticState = [];

    /**
     * Method 'mount' ini sudah diperbaiki dengan 'Tanggal RS'.
     */
    public function mount(MonitoringCycleIcu $cycle)
    {
        $this->cycle = $cycle; // Terima cycle dari Induk (Workspace)
    }

    /**
     * [Computed Property BARU]
     * Mengambil semua record inputan real-time.
     * Ini akan menjadi "Kolom" (header) kita.
     */
    #[Computed(persist: true)]
    public function allRecords(): Collection
    {
        $shifts = [
            'pagi' => [7, 13],  // Jam 07:00 - 13:59
            'siang' => [14, 20], // Jam 14:00 - 20:59
            'malam' => [21, 6],  // Jam 21:00 - 06:59
        ];
        // Ambil semua record, urutkan berdasarkan waktu
        return $this->cycle->records()
            ->with('inputter')
            ->orderBy('observation_time', 'asc')

            // --- Logika filter 'when' Anda (Ini sudah benar) ---
            ->when($this->filterShift != 'all', function ($query) use ($shifts) {
                $jam = $shifts[$this->filterShift];

                // Logika filter malam (lintas hari)
                if ($this->filterShift == 'malam') {
                    return $query->where(function ($q) use ($jam) {
                        $q->whereTime('observation_time', '>=', $jam[0] . ':00:00')
                            ->orWhereTime('observation_time', '<=', $jam[1] . ':59:59');
                    });
                }

                // Logika filter pagi/siang
                return $query->whereTime('observation_time', '>=', $jam[0] . ':00:00')
                    ->whereTime('observation_time', '<=', $jam[1] . ':59:59');
            })

            ->get();
    }

    /**
     * Helper: Daftar Parameter (Baris)
     * (Sudah di-update dengan Ventilator, EKG, dll.)
     */
    public function parameters(): array
    {
        return [
            // [Nama di DB, Label di Tampilan, Grup]
            ['key' => 'suhu', 'label' => 'Suhu (°C)', 'group' => 'HEMODINAMIK'],
            ['key' => 'nadi', 'label' => 'Nadi (x/mnt)', 'group' => 'HEMODINAMIK'],
            ['key' => 'tensi', 'label' => 'Tensi', 'group' => 'HEMODINAMIK'],
            ['key' => 'map', 'label' => 'MAP', 'group' => 'HEMODINAMIK'],
            ['key' => 'irama_ekg', 'label' => 'Irama EKG', 'group' => 'HEMODINAMIK'],

            ['key' => 'rr', 'label' => 'RR (x/mnt)', 'group' => 'RESPIRASI'],
            ['key' => 'spo2', 'label' => 'SpO2 (%)', 'group' => 'RESPIRASI'],
            ['key' => 'cvp', 'label' => 'CVP', 'group' => 'RESPIRASI'],
            ['key' => 'et_tt', 'label' => 'ET / TT', 'group' => 'RESPIRASI'],
            ['key' => 'cuff_pressure', 'label' => 'Cuff Pressure', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_mode', 'label' => 'Mode Ventilator', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_f', 'label' => 'Vent. F (Freq)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_tv', 'label' => 'Vent. TV (Vol)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_fio2', 'label' => 'Vent. FiO2 (%)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_peep', 'label' => 'Vent. PEEP', 'group' => 'RESPIRASI'],

            ['key' => 'gcs', 'label' => 'GCS', 'group' => 'OBSERVASI'],
            ['key' => 'pupil', 'label' => 'Pupil (Ki/Ka)', 'group' => 'OBSERVASI'],
            ['key' => 'kesadaran', 'label' => 'Kesadaran', 'group' => 'OBSERVASI'],
            ['key' => 'nyeri', 'label' => 'Nyeri (0-10)', 'group' => 'OBSERVASI'],

            ['key' => 'cairan_masuk', 'label' => 'Cairan Masuk', 'group' => 'CAIRAN'],
            ['key' => 'cairan_keluar', 'label' => 'Cairan Keluar', 'group' => 'CAIRAN'],

            ['key' => 'clinical_note', 'label' => 'Catatan Klinis/Masalah', 'group' => 'CATATAN'],
            ['key' => 'doctor_instruction', 'label' => 'Instruksi/Tindakan', 'group' => 'CATATAN'],
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
    /**
     * [Computed Property]
     * Menyiapkan data untuk Chart.js.
     * (Sudah di-update dengan CVP)
     */
    #[Computed(persist: true)]
    public function chartData(): array
    {
        $labels = [];
        $suhuData = [];
        $nadiData = [];
        $rrData = [];
        $sistolData = [];
        $diastolData = [];
        $cvpData = []; // <-- TAMBAHAN BARU

        // Kita ambil data TTV saja dari semua record
        $ttvRecords = $this->allRecords->filter(function ($record) {
            return $record->suhu || $record->nadi || $record->tensi_sistol || $record->rr || $record->cvp;
        });

        foreach ($ttvRecords as $record) {
            $labels[] = $record->observation_time->format('H:i');

            $suhuData[] = $record->suhu ?? null;
            $nadiData[] = $record->nadi ?? null;
            $rrData[] = $record->rr ?? null;
            $sistolData[] = $record->tensi_sistol ?? null;
            $diastolData[] = $record->tensi_diastol ?? null;
            $cvpData[] = $record->cvp ?? null; // <-- TAMBAHAN BARU
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Suhu (°C)',
                    'data' => $suhuData,
                    'borderColor' => 'rgb(239, 68, 68)', // red-600
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'yAxisID' => 'ySuhu',
                ],
                [
                    'label' => 'Nadi (x/mnt)',
                    'data' => $nadiData,
                    'borderColor' => 'rgb(34, 197, 94)', // green-600
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'RR (x/mnt)',
                    'data' => $rrData,
                    'borderColor' => 'rgb(168, 85, 247)', // purple-600
                    'backgroundColor' => 'rgba(168, 85, 247, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'Tensi Sistol',
                    'data' => $sistolData,
                    'borderColor' => 'rgb(59, 130, 246)', // blue-600
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'Tensi Diastol',
                    'data' => $diastolData,
                    'borderColor' => 'rgb(234, 179, 8)', // yellow-600
                    'backgroundColor' => 'rgba(234, 179, 8, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                // --- DATASET BARU UNTUK CVP ---
                [
                    'label' => 'CVP',
                    'data' => $cvpData,
                    'borderColor' => 'rgb(249, 115, 22)', // orange-600
                    'backgroundColor' => 'rgba(249, 115, 22, 0.2)',
                    'yAxisID' => 'yCvp', // Pakai sumbu Y ketiga
                ],
            ],
        ];
    }


    public function render()
    {
        return view('livewire.icu.observation-grid', [
            'allParameters' => $this->parameters() // Kirim daftar parameter ke view
        ])->layout('layouts.app');
    }
}
