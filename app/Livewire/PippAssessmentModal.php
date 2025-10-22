<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;

class PippAssessmentModal extends Component
{
    public $noRawat;
    public $patientId;

    // --- PROPERTI UNTUK MODAL STATE DAN WAKTU ---
    public bool $showModal = false;
    public $pipp_assessment_time;

    // --- PROPERTI DATA PIPP SCORE ---
    // Diinisialisasi dengan '0' agar perhitungan Alpine.js tidak error
    public $gestational_age = 0;
    public $behavioral_state = 0;
    public $max_heart_rate = 0;
    public $min_oxygen_saturation = 0;
    public $brow_bulge = 0;
    public $eye_squeeze = 0;
    public $nasolabial_furrow = 0;

    public function mount($noRawat, $patientId)
    {
        $this->noRawat = $noRawat;
        $this->patientId = $patientId;
        // Inisialisasi waktu awal
        $this->pipp_assessment_time = now()->format('Y-m-d\TH:i');
    }

    #[On('open-pipp-modal')]
    public function openModal()
    {
        // Reset waktu ke saat ini setiap kali modal dibuka
        $this->pipp_assessment_time = now()->format('Y-m-d\TH:i');
        // Reset skor ke 0 (default)
        $this->gestational_age = $this->behavioral_state = 0;
        $this->max_heart_rate = $this->min_oxygen_saturation = 0;
        $this->brow_bulge = $this->eye_squeeze = 0;
        $this->nasolabial_furrow = 0;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveScore()
    {
        // TODO: Ganti dengan Logic Validasi dan Penyimpanan Anda

        // --- Contoh Perhitungan Total Skor ---
        $totalScore = (int) $this->gestational_age + (int) $this->behavioral_state + (int) $this->max_heart_rate +
            (int) $this->min_oxygen_saturation + (int) $this->brow_bulge + (int) $this->eye_squeeze +
            (int) $this->nasolabial_furrow;

        // SIMULASI: Anggap data PIPP sudah tersimpan di database

        // Mengirim Event ke Komponen Utama untuk memperbarui $skala_nyeri dan notifikasi
        $this->dispatch('pipp-score-saved', totalScore: $totalScore);
        $this->dispatch('success-notification', message: 'Skor PIPP berhasil disimpan! (Skor: ' . $totalScore . ')');

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.pipp-assessment-modal')->layout('layouts.app');
    }
}
