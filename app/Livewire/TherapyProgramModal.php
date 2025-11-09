<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TherapyProgram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TherapyProgramModal extends Component
{
    // Properti yang diterima dari parent
    public $currentCycleId;
    public $no_rawat;

    // Properti untuk data riwayat
    public $therapy_program_history = [];

    // Properti ini perlu didefinisikan agar error validasi dapat ditampilkan
    public $masalah = '';
    public $program = '';
    public $enteral = '';
    public $parenteral = '';
    public $lab = '';


    /**
     * Mount method untuk menerima props dari parent
     */
    public function mount($currentCycleId, $noRawat)
    {
        $this->currentCycleId = $currentCycleId;
        $this->no_rawat = $noRawat;

        // Memuat riwayat saat komponen di-load
        $this->loadTherapyHistoryOnly();
    }

    /**
     * Memuat riwayat.
     */
    public function loadTherapyHistoryOnly()
    {
        if ($this->currentCycleId) {
            $this->therapy_program_history = TherapyProgram::where('monitoring_cycle_id', $this->currentCycleId)
                ->with('pegawai') // Sesuaikan 'pegawai' dengan relasi Anda ke user
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($program) {
                    $program->author_name = $program->pegawai->nama ?? 'N/A';
                    return $program;
                });
        }
    }

    /**
     * Mengirim event ke Alpine untuk mengisi form.
     */
    public function loadHistoryToForm($programId)
    {
        $program = TherapyProgram::find($programId);

        if ($program) {
            // Isi properti Livewire, ini akan mengisi form karena x-model terikat ke properti ini
            $this->masalah = $program->masalah_klinis;
            $this->program = $program->program_terapi;
            $this->enteral = $program->nutrisi_enteral;
            $this->parenteral = $program->nutrisi_parenteral;
            $this->lab = $program->pemeriksaan_lab;
        }
    }

    /**
     * Menyimpan data program terapi dari Alpine.
     */
    public function saveTherapyProgram($data)
    {
        // PENTING: Jika currentCycleId hilang, lempar Livewire ValidationException.
        // Livewire akan menampilkan error dan menjaga modal tetap terbuka.
        if (!$this->currentCycleId) {
            throw ValidationException::withMessages([
                'currentCycleId' => ['Simpan data observasi pertama untuk membuat siklus terapi.'],
            ]);
        }

        // Livewire akan menangani kegagalan validasi, modal tidak akan tertutup.
        $validatedData = Validator::make($data, [
            'masalah_klinis' => 'required|string',
            'program_terapi' => 'required|string',
            'nutrisi_enteral' => 'required|string',
            'nutrisi_parenteral' => 'required|string',
            'pemeriksaan_lab' => 'required|string',
        ])->validate();

        $latestProgram = TherapyProgram::where('monitoring_cycle_id', $this->currentCycleId)
            ->latest()
            ->first();
        $currentUserId = Auth::id();

        // Cek apakah data tidak berubah
        if (
            $latestProgram &&
            $latestProgram->masalah_klinis === $validatedData['masalah_klinis'] &&
            $latestProgram->program_terapi === $validatedData['program_terapi'] &&
            $latestProgram->nutrisi_enteral === $validatedData['nutrisi_enteral'] &&
            $latestProgram->nutrisi_parenteral === $validatedData['nutrisi_parenteral'] &&
            $latestProgram->pemeriksaan_lab === $validatedData['pemeriksaan_lab'] &&
            $latestProgram->id_user === $currentUserId
        ) {
            // Notifikasi info, modal tetap terbuka
            $this->dispatch('notify', message: 'Info: Tidak ada perubahan pada program terapi.');
            return;
        }

        // Buat data baru
        try {
            TherapyProgram::create([
                'monitoring_cycle_id' => $this->currentCycleId,
                'no_rawat' => $this->no_rawat,
                'id_user' => $currentUserId,
            ] + $validatedData);

        } catch (\Exception $e) {
            // Notifikasi error, modal tetap terbuka
            $this->dispatch('error-notification', message: 'Gagal menyimpan: ' . $e->getMessage());
            return;
        }

        // Muat ulang riwayat
        $this->loadTherapyHistoryOnly();

        // PENTING: HANYA DISPATCH EVENT SUKSES JIKA BENAR-BENAR BERHASIL
        $this->dispatch('therapy-saved-success', message: 'Program Terapi berhasil disimpan!');
    }


    /**
     * Render view.
     */
    public function render()
    {
        return view('livewire.therapy-program-modal');
    }
}
