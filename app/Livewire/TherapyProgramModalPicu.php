<?php

namespace App\Livewire;

use App\Models\PicuTherapyProgram;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TherapyProgramModalPicu extends Component
{
    // Properti yang diterima dari parent
    public $currentCycleId;
    public $no_rawat;

    // Properti untuk data riwayat
    public $therapy_program_history = [];

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
            $this->therapy_program_history = PicuTherapyProgram::where('monitoring_cycle_id', $this->currentCycleId)
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
        $program = PicuTherapyProgram::find($programId);

        if ($program) {
            // Kirim event yang akan ditangkap oleh x-data di view
            $this->dispatch('load-therapy-form', [
                'masalah' => $program->masalah_klinis,
                'program' => $program->program_terapi,
                'enteral' => $program->nutrisi_enteral,
                'parenteral' => $program->nutrisi_parenteral,
                'lab' => $program->pemeriksaan_lab,
            ]);
        }
    }

    /**
     * Menyimpan data program terapi dari Alpine.
     */
    public function saveTherapyProgram($data)
    {
        if (!$this->currentCycleId) {
            $this->dispatch('error-notification', message: 'Simpan data observasi pertama untuk membuat siklus terapi.');
            return;
        }

        // Validasi data dari Alpine
        $validatedData = Validator::make($data, [
            'masalah_klinis' => 'required|string',
            'program_terapi' => 'required|string',
            'nutrisi_enteral' => 'required|string',
            'nutrisi_parenteral' => 'required|string',
            'pemeriksaan_lab' => 'required|string',
        ])->validate();

        $latestProgram = PicuTherapyProgram::where('monitoring_cycle_id', $this->currentCycleId)
            ->latest()
            ->first();

        $currentUserId = Auth::id();

        // Cek duplikasi
        if (
            $latestProgram &&
            $latestProgram->masalah_klinis === $validatedData['masalah_klinis'] &&
            $latestProgram->program_terapi === $validatedData['program_terapi'] &&
            $latestProgram->nutrisi_enteral === $validatedData['nutrisi_enteral'] &&
            $latestProgram->nutrisi_parenteral === $validatedData['nutrisi_parenteral'] &&
            $latestProgram->pemeriksaan_lab === $validatedData['pemeriksaan_lab'] &&
            $latestProgram->id_user === $currentUserId
        ) {
            $this->dispatch('notify', 'Info: Tidak ada perubahan pada program terapi.');
            return;
        }

        // Buat data baru
        PicuTherapyProgram::create([
            'monitoring_cycle_id' => $this->currentCycleId,
            'no_rawat' => $this->no_rawat,
            'id_user' => $currentUserId,
        ] + $validatedData);

        // Muat ulang riwayat
        $this->loadTherapyHistoryOnly();

        $this->dispatch('record-saved', message: 'Program Terapi berhasil disimpan!');
    }

    /**
     * Render view.
     */
    public function render()
    {
        return view('livewire.therapy-program-modal-picu');
    }
}
