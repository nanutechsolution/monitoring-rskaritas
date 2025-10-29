<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class MonitorSheet extends Component
{
    public MonitoringCycleIcu $cycle;
    public ?float $iwlInput = null;
    public array $ttvState = [];
    public array $activeParenteralVolumes = [];
    public array $newParenteralState = [];
    public array $activeEnteralVolumes = [];
    public array $newEnteralState = [];
    public array $cairanKeluarState = [];

    /**
     * Method 'mount' ini berjalan saat komponen pertama kali di-load.
     * Kita akan "mengikat" komponen ini ke satu lembar observasi spesifik.
     */
    public function mount(MonitoringCycleIcu $cycle)
    {
        $this->cycle = $cycle;
        $this->initializeForms();
        $this->iwlInput = $this->cycle->daily_iwl;
    }

    /**
     * Menyimpan nilai IWL harian.
     */
    public function saveIwl(): void
    {
        // Validasi
        $this->validate([
            'iwlInput' => 'required|numeric|min:0',
        ], [
            'iwlInput.required' => 'Nilai IWL harus diisi.',
            'iwlInput.numeric' => 'Nilai IWL harus angka.',
            'iwlInput.min' => 'Nilai IWL tidak boleh negatif.',
        ]);

        // Update nilai IWL di tabel induk (cycle)
        $this->cycle->update([
            'daily_iwl' => $this->iwlInput
        ]);
        $this->updateDailyBalance();
        session()->flash('message-iwl', 'Nilai IWL berhasil disimpan.');
    }
    /**
     * Mengambil daftar unik cairan/makanan enteral yang sudah diinput hari ini.
     */
    #[Computed]
    public function usedEnteralFluids(): Collection
    {
        return $this->cycle->records()
            ->where('is_enteral', true)
            ->whereNotNull('cairan_masuk_jenis')
            ->distinct('cairan_masuk_jenis')
            ->pluck('cairan_masuk_jenis');
    }

    /**
     * Mengambil daftar unik cairan parenteral yang sudah diinput hari ini.
     */
    #[Computed(persist: true)]
    public function usedParenteralFluids(): Collection
    {
        return $this->cycle->records()
            ->where('is_parenteral', true)
            ->whereNotNull('cairan_masuk_jenis')
            ->distinct('cairan_masuk_jenis')
            ->pluck('cairan_masuk_jenis');
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
            'ventilator_mode' => null,
            'ventilator_f' => null,
            'ventilator_tv' => null,
            'ventilator_fio2' => null,
            'ventilator_peep' => null,
            'ventilator_pinsp' => null,
            'ventilator_ie_ratio' => null,

            'pupil_left_size_mm' => null,
            'pupil_left_reflex' => null,
            'pupil_right_size_mm' => null,
            'pupil_right_reflex' => null,
            'clinical_note' => null,
            'medication_administration' => null,

        ];

        // Form Cairan Masuk
        $this->activeParenteralVolumes = [];
        $this->newParenteralState = [
            'jenis' => null,
            'volume' => null,
        ];

        $this->activeEnteralVolumes = [];
        $this->newEnteralState = [
            'jenis' => null,
            'volume' => null,
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
        $this->validate([
            'ttvState.suhu' => 'nullable|numeric|min:30|max:45',
            'ttvState.nadi' => 'nullable|numeric|integer|min:0|max:400',
            'ttvState.rr' => 'nullable|numeric|integer|min:0|max:100',
            'ttvState.spo2' => 'nullable|numeric|integer|min:0|max:100',
            'ttvState.tensi_sistol' => 'nullable|numeric|integer|min:0|max:300',
            'ttvState.tensi_diastol' => 'nullable|numeric|integer|min:0|max:200',
            'ttvState.map' => 'nullable|numeric|integer|min:0|max:200',
            'ttvState.gcs_e' => 'nullable|numeric|integer|min:1|max:4',
            'ttvState.gcs_v' => 'nullable|numeric|integer|min:1|max:5',
            'ttvState.gcs_m' => 'nullable|numeric|integer|min:1|max:6',
            'ttvState.gcs_total' => 'nullable|numeric|integer|min:3|max:15',
            'ttvState.nyeri' => 'nullable|numeric|integer|min:0|max:10',
            'ttvState.kesadaran' => 'nullable|string|max:100',
            'ttvState.irama_ekg' => 'nullable|string|max:50',
            'ttvState.cvp' => 'nullable|numeric|integer|min:-10|max:50',
            'ttvState.cuff_pressure' => 'nullable|numeric|min:0|max:100',
            'ttvState.ventilator_mode' => 'nullable|string|max:50',
            'ttvState.ventilator_f' => 'nullable|numeric|integer|min:0|max:100',
            'ttvState.ventilator_tv' => 'nullable|numeric|integer|min:0|max:1000',
            'ttvState.ventilator_fio2' => 'nullable|numeric|integer|min:21|max:100',
            'ttvState.ventilator_peep' => 'nullable|numeric|integer|min:0|max:50',
            'ttvState.ventilator_pinsp' => 'nullable|numeric|integer|min:0|max:60',
            'ttvState.ventilator_ie_ratio' => 'nullable|string|max:10',

            'ttvState.pupil_left_size_mm' => 'nullable|numeric|integer|min:1|max:9',
            'ttvState.pupil_left_reflex' => 'nullable|string|in:+,-',
            'ttvState.pupil_right_size_mm' => 'nullable|numeric|integer|min:1|max:9',
            'ttvState.pupil_right_reflex' => 'nullable|string|in:+,-',
            'ttvState.clinical_note' => 'nullable|string',
            'ttvState.medication_administration' => 'nullable|string',
        ], [
            'ttvState.*.numeric' => 'Kolom harus angka.',
            'ttvState.*.min' => 'Nilai terlalu rendah.',
            'ttvState.*.max' => 'Nilai terlalu tinggi.',
            'ttvState.pupil_*.in' => 'Reflek harus diisi + atau -',

        ]);

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
            session()->flash('message-ttv', 'Data Observasi berhasil disimpan.');
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
        $iwl = $this->cycle->daily_iwl ?? 0;
        $balance24Jam = $totalMasuk - ($totalKeluar + $iwl);

        // 3. Simpan NET balance ini ke tabel induk
        $this->cycle->update([
            'calculated_balance_24h' => $balance24Jam
        ]);

        // 4. Hapus cache computed property agar nilainya me-refresh
        unset($this->fluidBalance);
    }

    /**
     * Menyimpan volume untuk cairan parenteral yang sudah aktif.
     * Bisa menyimpan multiple record sekaligus.
     */
    public function saveActiveParenteralVolumes(array $volumes): void
    {
        $validator = \Illuminate\Support\Facades\Validator::make(['volumes' => $volumes], [
            'volumes.*' => 'nullable|numeric|integer|min:1',
        ]);
        if ($validator->fails()) {
            // Kirim error kembali ke browser jika validasi gagal
            // (Pesan akan muncul di bawah input karena key-nya sama)
            foreach ($validator->errors()->messages() as $key => $message) {
                // Ubah key error agar cocok dengan @error di view
                $errorKey = str_replace('volumes.', 'activeParenteralVolumes.', $key);
                $this->addError($errorKey, $message[0]);
            }
            return; // Hentikan eksekusi jika gagal
        }

        $recordsCreated = 0;
        $timestamp = now();
        $inputterNik = Auth::user()->nik;

        // Loop melalui volume yang diinput
        foreach ($volumes as $jenis => $volume) {
            if (!empty($volume) && is_numeric($volume) && $volume > 0) {
                $this->cycle->records()->create([
                    'cairan_masuk_jenis' => $jenis,
                    'cairan_masuk_volume' => $volume,
                    'is_parenteral' => true,
                    'is_enteral' => false,
                    'observation_time' => $timestamp,
                    'nik_inputter' => $inputterNik,
                ]);
                $recordsCreated++;
            }
        }

        if ($recordsCreated > 0) {
            // Reset form volume aktif
            $this->reset('activeParenteralVolumes');
            // Update balance & kirim notifikasi
            $this->updateDailyBalance();
            session()->flash('message-parenteral', $recordsCreated . ' data volume parenteral berhasil disimpan.');
        } else {
            session()->flash('message-parenteral', 'Tidak ada volume yang diinput.');
        }
    }

    /**
     * Menambahkan jenis cairan parenteral baru dan volumenya.
     */
    public function addNewParenteral(?string $jenis, ?string $volume): void
    {
        // Validasi data yang diterima dari Alpine
        $validator = \Illuminate\Support\Facades\Validator::make(['jenis' => $jenis, 'volume' => $volume], [
            'jenis' => 'required|string|max:100',
            'volume' => 'required|numeric|integer|min:1',
        ], [
            'jenis.required' => 'Nama cairan baru harus diisi.',
            'volume.required' => 'Volume awal harus diisi.',
        ]);
        if ($validator->fails()) {
            // Kirim error kembali (key disesuaikan dengan @error di view)
            if ($validator->errors()->has('jenis'))
                $this->addError('newParenteralState.jenis', $validator->errors()->first('jenis'));
            if ($validator->errors()->has('volume'))
                $this->addError('newParenteralState.volume', $validator->errors()->first('volume'));
            return;
        }

        // Simpan record baru menggunakan data DARI PARAMETER
        $this->cycle->records()->create([
            'cairan_masuk_jenis' => $jenis,
            'cairan_masuk_volume' => $volume,
            'is_parenteral' => true,
            'is_enteral' => false,
            'observation_time' => now(),
            'nik_inputter' => Auth::user()->nik,
        ]);

        $this->reset('newParenteralState');
        unset($this->usedParenteralFluids);
        $this->updateDailyBalance();
        session()->flash('message-parenteral', 'Cairan parenteral baru berhasil ditambahkan.');
    }

    /**
     * Menyimpan volume untuk cairan/makanan enteral yang sudah aktif.
     * Menerima data $volumes DARI ALPINE.
     */
    public function saveActiveEnteralVolumes(array $volumes): void // <-- Tambahkan parameter $volumes
    {
        // Validasi data DARI ALPINE
        $validator = Validator::make(['volumes' => $volumes], [
            'volumes.*' => 'nullable|numeric|integer|min:1',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $message) {
                $errorKey = str_replace('volumes.', 'activeEnteralVolumes.', $key);
                $this->addError($errorKey, $message[0]);
            }
            return;
        }

        $recordsCreated = 0;
        $timestamp = now();
        $inputterNik = Auth::user()->nik;
        // Loop melalui volume DARI ALPINE
        foreach ($volumes as $jenis => $volume) {
            if (!empty($volume) && is_numeric($volume) && $volume > 0) {
                $this->cycle->records()->create([
                    'cairan_masuk_jenis' => $jenis,
                    'cairan_masuk_volume' => $volume,
                    'is_enteral' => true,
                    'is_parenteral' => false,
                    'observation_time' => $timestamp,
                    'nik_inputter' => $inputterNik,
                ]);
                $recordsCreated++;
            }
        }

        if ($recordsCreated > 0) {
            $this->reset('activeEnteralVolumes');
            $this->updateDailyBalance();
            session()->flash('message-enteral', $recordsCreated . ' data volume enteral berhasil disimpan.');
        } else {
            session()->flash('message-enteral', 'Tidak ada volume yang diinput.');
        }
    }

    /**
     * Menambahkan jenis cairan/makanan enteral baru dan volumenya.
     * Menerima data $jenis dan $volume DARI ALPINE.
     */
    public function addNewEnteral(?string $jenis, ?string $volume): void // <-- Tambahkan parameter $jenis, $volume
    {
        // Validasi data DARI ALPINE
        $validator = Validator::make(['jenis' => $jenis, 'volume' => $volume], [
            'jenis' => 'required|string|max:100',
            'volume' => 'required|numeric|integer|min:1',
        ], [
            'jenis.required' => 'Nama makanan/minuman baru harus diisi.',
            'volume.required' => 'Volume awal harus diisi.',
        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('jenis'))
                $this->addError('newEnteralState.jenis', $validator->errors()->first('jenis'));
            if ($validator->errors()->has('volume'))
                $this->addError('newEnteralState.volume', $validator->errors()->first('volume'));
            return;
        }

        // Simpan record baru menggunakan data DARI PARAMETER
        $this->cycle->records()->create([
            'cairan_masuk_jenis' => $jenis,
            'cairan_masuk_volume' => $volume,
            'is_enteral' => true,
            'is_parenteral' => false,
            'observation_time' => now(),
            'nik_inputter' => Auth::user()->nik, // <-- Pastikan ini NIK
        ]);

        // Reset state Livewire (Alpine akan direset terpisah di view)
        $this->reset('newEnteralState');
        unset($this->usedEnteralFluids);
        $this->updateDailyBalance();
        session()->flash('message-enteral', 'Makanan/minuman enteral baru berhasil ditambahkan.');
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
