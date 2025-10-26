<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use App\Models\RegPeriksa;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\On;

class MonitorSheet extends Component
{
    // Properti utama untuk menampung data "induk"
    public MonitoringCycleIcu $cycle;

    // Properti untuk menampung data dari 3 form input kita
    // Kita pakai array agar rapi
    public array $ttvState = [];
    public array $cairanMasukState = [];
    public array $cairanKeluarState = [];

    public ?string $clinicalNoteState = null;
    public ?string $instructionState = null;

    /**
     * Method 'mount' ini berjalan saat komponen pertama kali di-load.
     * Kita akan "mengikat" komponen ini ke satu lembar observasi spesifik.
     */
    public function mount(MonitoringCycleIcu $cycle)
    {
        $this->cycle = $cycle;
        $this->initializeForms();
    }

    /**
     * Menyimpan Catatan Klinis / Masalah.
     */
    public function saveClinicalNote(): void
    {
        // Validasi: Pastikan tidak kosong
        $this->validate(['clinicalNoteState' => 'required|string']);

        // Buat record baru (hanya berisi note)
        $this->cycle->records()->create([
            'observation_time' => now(),
            'nik_inputter' => Auth::user()->nik,
            'clinical_note' => $this->clinicalNoteState,
        ]);

        // Kosongkan form & kirim notifikasi
        $this->reset('clinicalNoteState');
        session()->flash('message-note', 'Catatan klinis berhasil disimpan.');
    }

    /**
     * Menyimpan Instruksi Dokter / Tindakan.
     */
    public function saveDoctorInstruction(): void
    {
        // Validasi: Pastikan tidak kosong
        $this->validate(['instructionState' => 'required|string']);

        // Buat record baru (hanya berisi instruksi)
        $this->cycle->records()->create([
            'observation_time' => now(),
            'nik_inputter' => Auth::user()->nik, // NIK Dokter/Petugas yg memberi instruksi
            'doctor_instruction' => $this->instructionState,
        ]);

        // Kosongkan form & kirim notifikasi
        $this->reset('instructionState');
        session()->flash('message-instruction', 'Instruksi berhasil disimpan.');
    }

    /**
     * Listener ini akan "mendengar" event dari Tab Statis.
     * Fungsinya untuk memaksa kalkulator fluidBalance menghitung ulang
     * jika IWL atau data lain yang relevan berubah.
     */
    #[On('static-data-updated')]
    #[On('iwl-updated')]
    public function refreshFluidBalance()
    {
        // Muat ulang data 'cycle' untuk mendapatkan 'daily_iwl' terbaru
        $this->cycle->refresh();

        // Hapus cache computed property
        unset($this->fluidBalance);
    }
    /**
     * Helper untuk mengosongkan semua form input.
     */
    public function initializeForms(): void
    {
        // Form Tanda-Tanda Vital
        $this->ttvState = [
            'suhu' => null,
            'nadi' => null,
            'rr' => null,
            'tensi_sistol' => null,
            'tensi_diastol' => null,
            'map' => null,
            'spo2' => null,
            'gcs_e' => null,
            'gcs_v' => null,
            'gcs_m' => null,
            'gcs_total' => null,
            'kesadaran' => null,
            'nyeri' => null,

            'irama_ekg' => null,
            'cvp' => null,
            'cuff_pressure' => null,
            'et_tt' => null,
            'ventilator_mode' => null,
            'ventilator_f' => null,
            'ventilator_tv' => null,
            'ventilator_fio2' => null,
            'ventilator_peep' => null,

            'pupil_left_size_mm' => null,
            'pupil_left_reflex' => null,
            'pupil_right_size_mm' => null,
            'pupil_right_reflex' => null,
        ];

        // Form Cairan Masuk
        $this->cairanMasukState = [
            'cairan_masuk_jenis' => null,
            'cairan_masuk_volume' => null,
        ];

        // Form Cairan Keluar
        $this->cairanKeluarState = [
            'cairan_keluar_jenis' => null,
            'cairan_keluar_volume' => null,
        ];



    }

    /**
     * Menyimpan data TTV (Sekarang dengan Validasi)
     */
    public function saveTtv(): void
    {
        // --- TAMBAHKAN BLOK VALIDASI INI ---
        $this->validate([
            'ttvState.suhu' => 'nullable|numeric|min:30|max:45',
            'ttvState.nadi' => 'nullable|numeric|integer|min:0|max:400',
            'ttvState.rr' => 'nullable|numeric|integer|min:0|max:100',
            'ttvState.spo2' => 'nullable|numeric|integer|min:0|max:100',
            'ttvState.tensi_sistol' => 'nullable|numeric|integer|min:0|max:300',
            'ttvState.tensi_diastol' => 'nullable|numeric|integer|min:0|max:200',
            'ttvState.gcs_e' => 'nullable|numeric|integer|min:1|max:4',
            'ttvState.gcs_v' => 'nullable|numeric|integer|min:1|max:5',
            'ttvState.gcs_m' => 'nullable|numeric|integer|min:1|max:6',
            'ttvState.gcs_total' => 'nullable|numeric|integer|min:3|max:15',
            'ttvState.nyeri' => 'nullable|numeric|integer|min:0|max:10',
            'ttvState.kesadaran' => 'nullable|string|max:100',
            'ttvState.irama_ekg' => 'nullable|string|max:50',
            'ttvState.cvp' => 'nullable|numeric|integer|min:-10|max:50',
            'ttvState.cuff_pressure' => 'nullable|numeric|min:0|max:100',
            'ttvState.et_tt' => 'nullable|string|max:50',
            'ttvState.ventilator_mode' => 'nullable|string|max:50',
            'ttvState.ventilator_f' => 'nullable|numeric|integer|min:0|max:100',
            'ttvState.ventilator_tv' => 'nullable|numeric|integer|min:0|max:1000',
            'ttvState.ventilator_fio2' => 'nullable|numeric|integer|min:21|max:100',
            'ttvState.ventilator_peep' => 'nullable|numeric|integer|min:0|max:50',

            'ttvState.pupil_left_size_mm' => 'nullable|numeric|integer|min:1|max:9',
            'ttvState.pupil_left_reflex' => 'nullable|string|in:+,-',
            'ttvState.pupil_right_size_mm' => 'nullable|numeric|integer|min:1|max:9',
            'ttvState.pupil_right_reflex' => 'nullable|string|in:+,-',
        ], [
            // Custom messages (opsional)
            'ttvState.*.numeric' => 'Kolom harus angka.',
            'ttvState.*.min' => 'Nilai terlalu rendah.',
            'ttvState.*.max' => 'Nilai terlalu tinggi.',
            'ttvState.pupil_*.in' => 'Reflek harus diisi + atau -',
        ]);
        // --- AKHIR BLOK VALIDASI ---

        // 1. Filter data: Hanya simpan yang diisi
        $data = $this->filterEmptyData($this->ttvState);

        // 2. Tambahkan data auto (HANYA JIKA ADA DATA YANG DIISI)
        if (count($data) > 0) {
            $data['observation_time'] = now();
            $data['nik_inputter'] = Auth::user()->nik;

            // 3. Simpan ke database
            $this->cycle->records()->create($data);

            // 4. Kosongkan form TTV & kirim notifikasi
            $this->reset('ttvState');
            session()->flash('message-ttv', 'Data TTV berhasil disimpan.');
        } else {
            // Jika form kosong tapi user klik simpan
            session()->flash('message-ttv', 'Tidak ada data untuk disimpan.');
        }
    }


    /**
     * Helper untuk menghitung & menyimpan BC 24 Jam ke database.
     */
    private function updateDailyBalance(): void
    {
        // 1. Ambil semua record cairan dari cycle ini
        $records = $this->cycle->records()->get();

        // 2. Kalkulasi
        $totalMasuk = $records->sum('cairan_masuk_volume');
        $totalKeluar = $records->sum('cairan_keluar_volume');
        $iwl = $this->cycle->daily_iwl ?? 0; // Ambil IWL dari tabel induk
        $balance24Jam = $totalMasuk - ($totalKeluar + $iwl);

        // 3. Simpan NET balance ini ke tabel induk
        $this->cycle->update([
            'calculated_balance_24h' => $balance24Jam
        ]);

        // 4. Hapus cache computed property agar nilainya me-refresh
        unset($this->fluidBalance);
    }

    /**
     * Menyimpan data Cairan Masuk (Sekarang dengan Validasi)
     */
    public function saveCairanMasuk(): void
    {
        // --- TAMBAHKAN BLOK VALIDASI INI ---
        // 'required' berarti tidak boleh null ATAU string kosong
        $this->validate([
            'cairanMasukState.cairan_masuk_jenis' => 'required|string|max:100',
            'cairanMasukState.cairan_masuk_volume' => 'required|numeric|integer|min:1',
        ], [
            'cairanMasukState.cairan_masuk_jenis.required' => 'Jenis cairan harus diisi.',
            'cairanMasukState.cairan_masuk_volume.required' => 'Volume harus diisi.',
            'cairanMasukState.cairan_masuk_volume.min' => 'Volume minimal 1 ml.',
        ]);
        // --- AKHIR BLOK VALIDASI ---

        // (Logika save Anda di bawah sini)
        $data = $this->cairanMasukState; // Ambil semua datanya
        $data['observation_time'] = now();
        $data['nik_inputter'] = Auth::user()->nik;

        $this->cycle->records()->create($data);
        $this->updateDailyBalance();
        $this->reset('cairanMasukState');
        session()->flash('message-cairan', 'Data Cairan Masuk berhasil disimpan.');
    }

    /**
     * Menyimpan data Cairan Keluar (Sekarang dengan Validasi)
     */
    public function saveCairanKeluar(): void
    {
        // --- TAMBAHKAN BLOK VALIDASI INI ---
        $this->validate([
            'cairanKeluarState.cairan_keluar_jenis' => 'required|string|max:100',
            'cairanKeluarState.cairan_keluar_volume' => 'required|numeric|integer|min:1',
        ], [
            'cairanKeluarState.cairan_keluar_jenis.required' => 'Jenis cairan harus diisi.',
            'cairanKeluarState.cairan_keluar_volume.required' => 'Volume harus diisi.',
        ]);
        // --- AKHIR BLOK VALIDASI ---

        // (Logika save Anda di bawah sini)
        $data = $this->cairanKeluarState; // Ambil semua datanya
        $data['observation_time'] = now();
        $data['nik_inputter'] = Auth::user()->nik;

        $this->cycle->records()->create($data);
        $this->updateDailyBalance();

        $this->reset('cairanKeluarState');
        session()->flash('message-cairan', 'Data Cairan Keluar berhasil disimpan.');
    }
    #[Computed(persist: true)]
    public function fluidBalance()
    {
        // 1. Ambil semua record cairan dari cycle ini
        $records = $this->cycle->records()->get();

        // 2. Kalkulasi
        $totalMasuk = $records->sum('cairan_masuk_volume');
        $totalKeluar = $records->sum('cairan_keluar_volume');
        $iwl = $this->cycle->daily_iwl ?? 0;

        // BC Net hari ini
        $balance24Jam = $totalMasuk - ($totalKeluar + $iwl);

        // Ambil BC dari hari kemarin
        $previousBalance = $this->cycle->previous_balance ?? 0;

        // Hitung total kumulatif
        $cumulativeBalance = $previousBalance + $balance24Jam;

        return [
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'iwl' => $iwl,
            'balance24Jam' => $balance24Jam, // Net hari ini
            'previousBalance' => $previousBalance, // Bawaan kemarin
            'cumulativeBalance' => $cumulativeBalance, // Total akhir
        ];
    }
    /**
     * [Computed Property]
     * Mengambil daftar log inputan real-time untuk ditampilkan.
     */
    #[Computed]
    public function logRecords()
    {
        // Ambil 100 record terakhir, diurutkan dari yang terbaru
        return $this->cycle->records()
            ->with('inputter') // Eager load data pegawai yg input
            ->orderBy('observation_time', 'desc')
            ->take(100)
            ->get();
    }

    /**
     * Helper untuk membersihkan array state sebelum disimpan ke DB.
     * Ini akan mengubah string kosong "" menjadi NULL.
     */
    private function filterEmptyData(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null && $value !== '');
    }

    /**
     * Method 'render' ini yang akan menampilkan file view-nya.
     */
    public function render()
    {
        return view('livewire.icu.monitor-sheet')->layout('layouts.app');
    }
}
