<?php


namespace App\Livewire\Ok;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RegPeriksa;

class IntraAnestesiPatientHistoryList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';

    private function queryPatients()
    {
        $query = RegPeriksa::with(['intraAnesthesiaMonitoring'])->whereHas('intraAnesthesiaMonitoring');
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
        return view('livewire.ok.ok-patient-history-list', [
            'patients' => $this->queryPatients(),
        ])->layout('layouts.app', ['title' => 'Daftar Riwayat Pasien Intra Anestesi']);
    }
}
