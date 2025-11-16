<?php

namespace App\Livewire\Icu;

use App\Models\KamarInap;
use App\Models\MonitoringCycleIcu;
use App\Models\MonitoringDevice;
use App\Models\RegPeriksa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class Workspace extends Component
{
    public ?string $currentRoomName = null;
    public ?string $originatingWardName = null;
    public MonitoringCycleIcu $cycle;
    public RegPeriksa $registrasi;
    public ?string $patientAllergy = null;
    public ?string $patientWeight = null;
    public string $noRawatDb;

    // Properti untuk mengatur tab
    public string $activeTab = 'input';
    public array $staticState = [];
    public Collection $dpjpDokters;
    public bool $showDeviceModal = false;
    /**
     *  memuat 1 cycle spesifik berdasarkan noRawat dan sheetDate.
     */
    public function mount(string $noRawat, ?string $sheetDate = null)
    {
        $this->noRawatDb = str_replace('_', '/', $noRawat);
        $latestPemeriksaan = DB::table('pemeriksaan_ranap')
            ->where('no_rawat', $this->noRawatDb)
            ->orderByDesc('tgl_perawatan')
            ->orderByDesc('jam_rawat')     // Lalu jam terbaru
            ->first(); // Ambil satu baris teratas
        // Simpan data ke properti (Logic ini tetap sama)
        $this->patientAllergy = $latestPemeriksaan->alergi ?? ($this->registrasi->pasien->alergi ?? 'Tidak ada');
        $this->patientWeight = $latestPemeriksaan->berat ?? null;
        $this->registrasi = RegPeriksa::with([
            'pasien',
            'poliklinik',
            'penjab',
            'dpjpRanap.dokter'
        ])
            ->where('no_rawat', $this->noRawatDb)
            ->firstOrFail();
        $firstKamarInap = DB::table('kamar_inap')
            ->where('no_rawat', $this->noRawatDb)
            ->orderBy('tgl_masuk', 'asc')
            ->orderBy('jam_masuk', 'asc')
            ->first();

        if ($firstKamarInap) {
            $startDateCarbon = \Carbon\Carbon::parse($firstKamarInap->tgl_masuk);
        } else {
            // fallback jika tidak ada kamar inap (jarang terjadi)
            $startDateCarbon = \Carbon\Carbon::parse($this->registrasi->tgl_registrasi);
        }
        // $startDate = $firstKamarInap ? $firstKamarInap->tgl_masuk : $this->registrasi->tgl_registrasi;
        $this->dpjpDokters = $this->registrasi->dpjpRanap->map(function ($dpjp) {
            return $dpjp->dokter;
        })->filter();
        $kamarInapHistory = KamarInap::where('no_rawat', $this->noRawatDb)
            ->orderBy('tgl_masuk', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();
        // 2. Tentukan Ruangan Asal (Prioritas: Kamar Inap Sebelumnya)
        if ($kamarInapHistory->count() > 1) {
            // Pasien pindahan dari kamar/bangsal lain (ambil record ke-2 dari belakang)
            $previousKamarInap = $kamarInapHistory->skip(1)->first(); // Record ke-2 terbaru

            // Ambil nama bangsal dari kamar sebelumnya
            $previousKamar = DB::table('kamar')->where('kd_kamar', $previousKamarInap->kd_kamar)->first();
            $previousBangsal = DB::table('bangsal')->where('kd_bangsal', $previousKamar->kd_bangsal)->first();

            $this->originatingWardName = $previousBangsal->nm_bangsal ?? 'N/A';
        } else {
            // Pasien baru masuk ke kamar inap (langsung dari Poli/IGD)
            // Gunakan Poliklinik/IGD sebagai ruangan asal.
            $this->originatingWardName = $this->registrasi->poliklinik->nm_poli ?? 'N/A';
        }
        $currentKamarInap = $kamarInapHistory->first();
        if ($currentKamarInap) {
            $currentKamarInap->load('kamar.bangsal'); // Load relasi hanya pada record terakhir
            // Gabungkan nama bangsal dan nomor kamar (sesuaikan nama kolom)
            $this->currentRoomName = ($currentKamarInap->kamar->bangsal->nm_bangsal ?? '')
                . ' / ' . ($currentKamarInap->kamar->kd_kamar ?? '');
        } else {
            $this->currentRoomName = 'N/A';
        }
        // Cari record kamar_inap terbaru
        $currentKamarInap = KamarInap::where('no_rawat', $this->noRawatDb)
            ->orderBy('tgl_masuk', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->with(['kamar.bangsal'])
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
        // $startDateCarbon = \Carbon\Carbon::parse($startDate); // Tambahkan parsing untuk startDate
        // $hospitalDayNumber = abs($targetDateCarbon->diffInDays($startDateCarbon)) + 1;
        $hospitalDayNumber = $startDateCarbon->diffInDays($targetDateCarbon) + 1;

        $this->cycle = MonitoringCycleIcu::firstOrCreate(
            [
                'no_rawat' => $this->noRawatDb,
                'sheet_date' => $targetDate,
            ],
            [
                'asal_ruangan' => $this->originatingWardName,
                'hari_rawat_ke' => $hospitalDayNumber,
                'start_time' => $targetDateCarbon->copy()->startOfDay()->addHours($hospitalDayStartHour),
                'end_time' => $targetDateCarbon->copy()->startOfDay()->addHours($hospitalDayStartHour)->addDay(),
            ]
        );

        // 4. Logic BC Kumulatif (jika baru dibuat)
        if ($this->cycle->wasRecentlyCreated) {
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
        $this->cycle->load('registrasi.pasien', 'devices');
        $this->initializeStaticState();
    }

    #[On('close-modal')]
    public function closeModalHandler()
    {
        $this->showDeviceModal = false;
    }

    #[On('device-added')]
    public function refreshDevices()
    {
        // Muat ulang relasi devices agar daftar di Tab Statis terupdate
        $this->cycle->load('devices');
    }
    // --- DAFTAR ALAT/TUBE STANDAR ---
    public function getDeviceOptions(): array
    {
        return [
            'ALAT' => ['IV Line', 'CVC', 'Arteri Line', 'Swanz Ganz', 'Lainnya (Alat)'],
            'TUBE' => ['NGT', 'Urin Kateter', 'WSD', 'Drain', 'Lainnya (Tube)'],
        ];
    }

    /**
     * Menerima data dari modal Alpine dan menyimpan alat/tube baru.
     */
    public function saveNewDevice(array $deviceData)
    {
        // 1. Validasi data dari Alpine
        $validatedData = Validator::make($deviceData, [
            'device_category' => 'required|in:ALAT,TUBE',
            'device_name' => 'required|string|max:100',
            'ukuran' => 'nullable|string|max:20',
            'lokasi' => 'nullable|string|max:50',
            'tanggal_pasang' => 'nullable|date_format:Y-m-d',
        ])->validate();

        // 2. Tambahkan ID cycle dan simpan
        $validatedData['monitoring_cycle_icu_id'] = $this->cycle->id;
        MonitoringDevice::create($validatedData);

        // 3. Muat ulang relasi devices agar daftar di view terupdate
        $this->cycle->load('devices');

        // 4. Kirim notifikasi sukses
        $this->dispatch('notification-sent', ['message' => 'Alat/Tube berhasil ditambahkan.', 'type' => 'success']);
    }
    /**
     * Mengisi form data statis dengan nilai dari database.
     */
    public function initializeStaticState()
    {
        $this->staticState = [
            'daily_iwl' => $this->cycle->daily_iwl,
            'ventilator_notes' => $this->cycle->ventilator_notes,
            'terapi_obat_parenteral' => $this->cycle->terapi_obat_parenteral,
            'terapi_obat_enteral_lain' => $this->cycle->terapi_obat_enteral_lain,
            'pemeriksaan_penunjang' => $this->cycle->pemeriksaan_penunjang,
            'catatan_lain_lain' => $this->cycle->catatan_lain_lain,
            'enteral_target_volume' => $this->cycle->enteral_target_volume,
            'enteral_target_kalori' => $this->cycle->enteral_target_kalori,
            'enteral_target_protein' => $this->cycle->enteral_target_protein,
            'enteral_target_lemak' => $this->cycle->enteral_target_lemak,
            'parenteral_target_volume' => $this->cycle->parenteral_target_volume,
            'parenteral_target_kalori' => $this->cycle->parenteral_target_kalori,
            'parenteral_target_protein' => $this->cycle->parenteral_target_protein,
            'parenteral_target_lemak' => $this->cycle->parenteral_target_lemak,
            'wound_notes' => $this->cycle->wound_notes,

        ];
    }

    /**
     * Method untuk menyimpan data statis dari Tab 3.
     */
    public function saveStaticData()
    {
        // Validasi
        $this->validate([
            'staticState.ventilator_notes' => 'nullable|string',
            'staticState.*' => 'nullable|string',
            'staticState.wound_notes' => 'nullable|string',
            'staticState.enteral_target_volume' => 'nullable|numeric|min:0',
            'staticState.enteral_target_kalori' => 'nullable|integer|min:0',
            'staticState.enteral_target_protein' => 'nullable|integer|min:0',
            'staticState.enteral_target_lemak' => 'nullable|integer|min:0',
            'staticState.parenteral_target_volume' => 'nullable|numeric|min:0',
            'staticState.parenteral_target_kalori' => 'nullable|integer|min:0',
            'staticState.parenteral_target_protein' => 'nullable|integer|min:0',
            'staticState.parenteral_target_lemak' => 'nullable|integer|min:0',

        ]);

        $this->cycle->update($this->staticState);
        session()->flash('message-statis', 'Data statis berhasil diperbarui.');
        $this->dispatch('static-data-updated');
    }
    #[Computed]
    public function logRecords()
    {
        return $this->cycle->records()
            ->with('inputter:nik,nama')
            ->orderBy('observation_time', 'desc')
            ->take(value: 100)
            ->get();
    }
    public function render()
    {
        return view('livewire.icu.workspace')->layout('layouts.app');
    }
}
