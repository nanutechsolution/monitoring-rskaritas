<?php


namespace App\Livewire\Icu;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MonitoringCycleIcu;
use App\Models\RegPeriksa;

class IcuPatientHistoryList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';

    private function queryPatients()
    {
        $query = RegPeriksa::whereHas('monitoringCycleIcu');

        if (trim($this->search) !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_rawat', 'like', "%{$search}%")
                    ->orWhereHas('pasien', fn($q2) => $q2->where('nm_pasien', 'like', "%{$search}%"));
            });
        }

        return $query->orderBy('tgl_registrasi', 'desc')->paginate(10);
    }


    public function render()
    {
        return view('livewire.icu.icu-patient-history-list', [
            'patients' => $this->queryPatients(),
        ])->layout('layouts.app', ['title' => 'Daftar Riwayat Pasien ICU']);
    }
}
