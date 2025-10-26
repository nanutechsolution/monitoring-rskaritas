<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use App\Models\RegPeriksa;
use Livewire\Component;

class Workspace extends Component
{
    public MonitoringCycleIcu $cycle;
    public RegPeriksa $registrasi;

    public string $noRawatDb;

    // Properti untuk mengatur tab
    public string $activeTab = 'input';
    public array $staticState = [];
    /**
     * Method 'mount' ini adalah inti dari halaman kerja.
     * Dia memuat 1 cycle spesifik berdasarkan noRawat dan sheetDate.
     */
    public function mount(string $noRawat, ?string $sheetDate = null)
    {
        $this->noRawatDb = str_replace('_', '/', $noRawat);

        // 1. Load data registrasi & pasien
        $this->registrasi = RegPeriksa::with('pasien')
            ->where('no_rawat', $this->noRawatDb)
            ->firstOrFail();

        // 2. Tentukan tanggal yang akan dibuka
        $targetDate = $sheetDate ? \Carbon\Carbon::parse($sheetDate)->toDateString() : $this->getTodayHospitalDate();

        // 3. Logic 'firstOrCreate' yang sudah kita sempurnakan
        $this->cycle = MonitoringCycleIcu::firstOrCreate(
            [
                'no_rawat' => $this->noRawatDb,
                'sheet_date' => $targetDate,
            ],
            [
                'diagnosa' => $this->registrasi->penyakit->nm_penyakit ?? 'Belum ada diagnosa',
                'asal_ruangan' => $this->registrasi->poliklinik->nm_poli ?? 'N/A',
                'hari_rawat_ke' => now()->diffInDays($this->registrasi->tgl_registrasi) + 1,
                'start_time' => now()->startOfDay()->addHours(7), // Ganti hari jam 7
                'end_time' => now()->startOfDay()->addHours(7)->addDay(),
            ]
        );

        // 4. Logic BC Kumulatif (jika baru dibuat)
        if ($this->cycle->wasRecentlyCreated) {
            if ($this->registrasi->kd_dokter) {
                $this->cycle->dpjpDokter()->attach($this->registrasi->kd_dokter);
            }

            $previousHospitalDate = \Carbon\Carbon::parse($targetDate)->subDay()->toDateString();
            $previousCycle = MonitoringCycleIcu::where('no_rawat', $this->noRawatDb)
                ->where('sheet_date', $previousHospitalDate)
                ->first();

            $previousBalance = 0;
            if ($previousCycle) {
                $previousBalance = ($previousCycle->previous_balance ?? 0) + ($previousCycle->calculated_balance_24h ?? 0);
            }
            $this->cycle->previous_balance = $previousBalance;
            $this->cycle->save();
        }

        // 5. Load relasi yang dibutuhkan
        $this->cycle->load('registrasi.pasien', 'dpjpDokter');
        $this->initializeStaticState();
    }
    /**
     * Mengisi form data statis dengan nilai dari database.
     */
    public function initializeStaticState()
    {
        $this->staticState = [
            'daily_iwl' => $this->cycle->daily_iwl,
            'terapi_obat_parenteral' => $this->cycle->terapi_obat_parenteral,
            'terapi_obat_enteral_lain' => $this->cycle->terapi_obat_enteral_lain,
            'pemeriksaan_penunjang' => $this->cycle->pemeriksaan_penunjang,
            'catatan_lain_lain' => $this->cycle->catatan_lain_lain,
            'alat_terpasang' => $this->cycle->alat_terpasang,
            'tube_terpasang' => $this->cycle->tube_terpasang,
            'masalah_keperawatan' => $this->cycle->masalah_keperawatan,
            'tindakan_obat' => $this->cycle->tindakan_obat,
        ];
    }

    /**
     * Method untuk menyimpan data statis dari Tab 3.
     */
    public function saveStaticData()
    {
        // Validasi
        $this->validate([
            'staticState.daily_iwl' => 'nullable|numeric|min:0',
            'staticState.*' => 'nullable|string', // Validasi umum untuk textarea
        ]);

        // Update data cycle
        $this->cycle->update($this->staticState);

        // Kirim notifikasi sukses
        session()->flash('message-statis', 'Data statis berhasil diperbarui.');

        // Update kalkulasi balance di Tab Input (jika IWL berubah)
        // Kita perlu cara untuk memberitahu komponen MonitorSheet
        // Dispatch event untuk didengarkan oleh MonitorSheet
        $this->dispatch('static-data-updated');
    }
    /**
     * Helper untuk mendapatkan tanggal "Hari RS" hari ini.
     */
    private function getTodayHospitalDate(): string
    {
        $currentTime = now();
        $hospitalDayStartHour = 7;
        if ($currentTime->hour < $hospitalDayStartHour) {
            return $currentTime->subDay()->toDateString();
        }
        return $currentTime->toDateString();
    }

    public function render()
    {
        return view('livewire.icu.workspace')->layout('layouts.app');
    }
}
