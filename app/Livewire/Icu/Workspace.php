<?php

namespace App\Livewire\Icu;

use App\Models\KamarInap;
use App\Models\MonitoringCycleIcu;
use App\Models\RegPeriksa;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Workspace extends Component
{
    public ?string $currentRoomName = null;
    public ?string $originatingWardName = null;
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

        $this->registrasi = RegPeriksa::with(['pasien', 'poliklinik'])
            ->where('no_rawat', $this->noRawatDb)
            ->firstOrFail();

        // Ambil Nama Asal Ruangan
        $this->originatingWardName = $this->registrasi->poliklinik->nm_poli ?? 'N/A';

        // --- TAMBAHKAN LOGIKA CARI KAMAR SAAT INI ---
        // Cari record kamar_inap terbaru
        $currentKamarInap = KamarInap::where('no_rawat', $this->noRawatDb)
            ->orderBy('tgl_masuk', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->with(['kamar.bangsal']) // Asumsi relasi KamarInap->kamar->bangsal
            ->first();

        if ($currentKamarInap && $currentKamarInap->kamar) {
            // Gabungkan nama bangsal dan nomor kamar (sesuaikan nama kolom)
            $this->currentRoomName = ($currentKamarInap->kamar->bangsal->nm_bangsal ?? '')
                . ' / ' . ($currentKamarInap->kamar->kd_kamar ?? '');
        } else {
            $this->currentRoomName = 'N/A';
        }
        $currentTime = now();
        $hospitalDayStartHour = 6;
        if ($currentTime->hour < $hospitalDayStartHour) {
            $hospitalDate = $currentTime->subDay()->toDateString();
        } else {
            $hospitalDate = $currentTime->toDateString();
        }
        $targetDate = $sheetDate ? \Carbon\Carbon::parse($sheetDate)->toDateString() : $hospitalDate; // Gunakan $hospitalDate jika $sheetDate null
        $targetDateCarbon = \Carbon\Carbon::parse($targetDate);

        $this->cycle = MonitoringCycleIcu::firstOrCreate(
            [
                'no_rawat' => $this->noRawatDb,
                'sheet_date' => $targetDate, // Tanggal RS sudah benar
            ],
            [
                'diagnosa' => $this->registrasi->penyakit->nm_penyakit ?? 'Belum ada diagnosa',
                'asal_ruangan' => $this->originatingWardName,
                'hari_rawat_ke' => now()->diffInDays($this->registrasi->tgl_registrasi) + 1,
                'start_time' => $targetDateCarbon->copy()->startOfDay()->addHours($hospitalDayStartHour),
                'end_time' => $targetDateCarbon->copy()->startOfDay()->addHours($hospitalDayStartHour)->addDay(),
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
        ];
    }

    /**
     * Method untuk menyimpan data statis dari Tab 3.
     */
    public function saveStaticData()
    {
        // Validasi
        $this->validate([
            'staticState.*' => 'nullable|string',
        ]);

        $this->cycle->update($this->staticState);
        session()->flash('message-statis', 'Data statis berhasil diperbarui.');
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


    /**
     * [Computed Property]
     * Mengambil daftar log inputan real-time untuk ditampilkan di Tab Log.
     */
    #[Computed] // Biarkan persist: false agar selalu update
    public function logRecords()
    {
        // Ambil 100 record terakhir, diurutkan dari yang terbaru
        // Pastikan relasi 'inputter' di-load
        return $this->cycle->records()
            ->with('inputter:nik,nama')
            ->orderBy('observation_time', 'desc')
            ->take(100)
            ->get();
    }
    public function render()
    {
        return view('livewire.icu.workspace')->layout('layouts.app');
    }
}
