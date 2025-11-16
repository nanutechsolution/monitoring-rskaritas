<?php

namespace App\Livewire\Nicu;

use Livewire\Component;
use App\Models\RegPeriksa;
use Livewire\WithPagination;
use Carbon\Carbon;

class NicuPatientHistoryList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    private function queryPatients()
    {
        $query = RegPeriksa::whereHas('monitoringCycles', function ($q) {
            // pastikan pasien punya NICU cycle
        })->where(function ($q) {
            if (trim($this->search) !== '') {
                $search = $this->search;

                // Search di pasien
                $q->whereHas('pasien', function ($p) use ($search) {
                    $p->where('nm_pasien', 'like', "%$search%")
                      ->orWhere('no_rkm_medis', 'like', "%$search%");
                })
                // Search di NICU cycle
                ->orWhereHas('monitoringCycles', function ($c) use ($search) {
                    $c->where('no_rawat', 'like', "%$search%")
                      ->orWhere('daily_iwl', 'like', "%$search%")
                      ->orWhere('calculated_balance_24h', 'like', "%$search%");
                });
            }
        });

        return $query->orderBy('tgl_registrasi', 'desc')->paginate(10);
    }

    public function render()
    {
        $patients = $this->queryPatients();

        // Tambahkan umur & deskripsi jk setelah data dipaginasi
        $patients->getCollection()->transform(function ($patient) {
            $patient->umur = Carbon::parse($patient->pasien->tgl_lahir)->age ?? '-';
            $patient->jk_desc = $patient->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan';
            return $patient;
        });

        return view('livewire.nicu.nicu-patient-history-list', [
            'patients' => $patients,
        ])->layout('layouts.app');
    }
}
