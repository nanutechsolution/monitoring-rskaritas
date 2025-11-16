<?php

namespace App\Livewire\Picu;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RegPeriksa;
use App\Models\Bangsal;
use Carbon\Carbon;

class PatientPicuHistoryList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public $filterDate = null;
    public $filterWard = '';
    public $wards = [];

    private function queryPatients()
    {
        $query = RegPeriksa::whereHas('picuMonitoringCycles')
            ->where(function ($q) {
                if (trim($this->search) !== '') {
                    $search = $this->search;
                    // Cari di tabel pasien
                    $q->whereHas('pasien', function ($p) use ($search) {
                        $p->where('nm_pasien', 'like', "%$search%")
                            ->orWhere('no_rkm_medis', 'like', "%$search%");
                    })
                        // Atau cari di tabel PICU cycle
                        ->orWhereHas('picuMonitoringCycles', function ($c) use ($search) {
                        $c->where('no_rawat', 'like', "%$search%")
                            ->orWhere('daily_iwl', 'like', "%$search%")
                            ->orWhere('calculated_balance_24h', 'like', "%$search%");
                    });
                }
            });
        return $query->orderBy('tgl_registrasi', 'desc')->paginate(10);
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterDate', 'filterWard'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterDate', 'filterWard']);
        $this->resetPage();
    }

    public function render()
    {
        $patients = $this->queryPatients();

        return view('livewire.picu.patient-picu-history-list', [
            'patients' => $patients,
        ])->layout('layouts.app');
    }
}
