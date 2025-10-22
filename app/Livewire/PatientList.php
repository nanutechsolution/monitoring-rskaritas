<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\View\View;

class PatientList extends Component
{
    public string $search = '';

    // [PERUBAHAN] Mulai dengan array kosong.
    public $patients = [];

    // [TAMBAHAN] Flag untuk melacak apakah pencarian sudah dilakukan
    public bool $searchPerformed = false;

    // Update daftar pasien saat search berubah
    public function runSearch()
    {
        // Tandai bahwa pencarian telah dilakukan
        $this->searchPerformed = true;

        // Validasi agar tidak mencari string kosong
        if (trim($this->search) === '') {
            $this->patients = []; // Kosongkan hasil jika input kosong
            return;
        }
        $this->patients = DB::table('kamar_inap as ki')
            ->join('reg_periksa as rp', 'ki.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->join('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->select('p.nm_pasien', 'p.no_rkm_medis', 'ki.no_rawat', 'b.nm_bangsal', 'p.tgl_lahir')
            ->where('ki.stts_pulang', '-') // pasien masih dirawat
            ->where(function ($query) {
                $query->where('p.nm_pasien', 'like', '%' . $this->search . '%')
                    ->orWhere('p.no_rkm_medis', 'like', '%' . $this->search . '%')
                    ->orWhere('ki.no_rawat', 'like', '%' . $this->search . '%');
            })
            ->orderBy('b.nm_bangsal')
            ->orderBy('p.nm_pasien')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.patient-list')
            ->layout('layouts.app');
    }
}
