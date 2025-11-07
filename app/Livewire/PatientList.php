<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\View\View;
use Carbon\Carbon;
use Livewire\WithPagination;

class PatientList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public $filterDate = null;
    public $filterWard = '';
    public $wards = [];

    public function mount()
    {
        $this->wards = DB::table('bangsal')
            ->where('status', '1')
            ->orderBy('nm_bangsal')
            ->pluck('nm_bangsal')
            ->all();
    }

    private function queryPatients()
    {
        $query = DB::table('kamar_inap as ki')
            ->join('reg_periksa as rp', 'ki.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->join('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->select(
                'p.nm_pasien',
                'p.no_rkm_medis',
                'ki.no_rawat',
                'b.nm_bangsal',
                'p.tgl_lahir',
                'p.jk',
                'ki.tgl_masuk'
            )
            ->where('ki.stts_pulang', '-');

        // Terapkan filter pencarian
        if (trim($this->search) !== '') {
            $query->where(function ($q) {
                $q->where('p.nm_pasien', 'like', '%' . $this->search . '%')
                    ->orWhere('p.no_rkm_medis', 'like', '%' . $this->search . '%')
                    ->orWhere('ki.no_rawat', 'like', '%' . $this->search . '%');
            });
        }

        // Terapkan filter tanggal masuk
        if ($this->filterDate) {
            $dateCarbon = Carbon::parse($this->filterDate);
            $query->whereBetween('ki.tgl_masuk', [$dateCarbon->startOfDay(), $dateCarbon->endOfDay()]);
        }

        // Terapkan filter bangsal
        if ($this->filterWard !== '') {
            $query->where('b.nm_bangsal', $this->filterWard);
        }

        // Urutkan (misal tgl masuk terbaru) dan paginasi
        return $query->orderBy('ki.tgl_masuk', 'desc')
            ->paginate(10); // 10 pasien per halaman
    }

    // Reset halaman jika filter berubah
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterDate', 'filterWard'])) {
            $this->resetPage();
        }
    }

    // Reset semua filter
    public function resetFilters()
    {
        $this->reset(['search', 'filterDate', 'filterWard']);
        $this->resetPage();
    }

    public function render(): View
    {
        $patients = $this->queryPatients();

        // Hitung umur & format JK setelah data dipaginasi
        $patients->getCollection()->transform(function ($patient) {
            $patient->umur = Carbon::parse($patient->tgl_lahir)->age;
            $patient->jk_desc = ($patient->jk == 'L' ? 'Laki-laki' : 'Perempuan');
            return $patient;
        });

        return view('livewire.patient-list', [
            'patients' => $patients
        ])->layout('layouts.app');
    }
}
