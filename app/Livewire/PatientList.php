<?php

namespace App\Livewire;

use App\Models\Bangsal;
use App\Models\KamarInap;
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
        $this->wards = Bangsal::where('status', '1')
            ->orderBy('nm_bangsal')
            ->pluck('nm_bangsal')
            ->toArray();
    }

    private function queryPatients()
    {
        $query = KamarInap::with([
            'regPeriksa.pasien',
            'regPeriksa.penjab',
            'kamar.bangsal',
        ])
            ->where('tgl_keluar', '0000-00-00')
            ->where('jam_keluar', '0000:00:00')
            ->where('stts_pulang', '-');

        // 🔍 Filter pencarian (nama, no RM, no rawat)
        if (trim($this->search) !== '') {
            $search = $this->search;
            $query->whereHas('regPeriksa.pasien', function ($q) use ($search) {
                $q->where('nm_pasien', 'like', "%$search%")
                    ->orWhere('no_rkm_medis', 'like', "%$search%");
            })->orWhere('no_rawat', 'like', "%$search%");
        }

        // 📅 Filter tanggal masuk
        if ($this->filterDate) {
            $dateCarbon = Carbon::parse($this->filterDate);
            $query->whereBetween('tgl_masuk', [
                $dateCarbon->startOfDay(),
                $dateCarbon->endOfDay(),
            ]);
        }

        // 🏥 Filter bangsal
        if ($this->filterWard !== '') {
            $filterWard = $this->filterWard;
            $query->whereHas('kamar.bangsal', function ($q) use ($filterWard) {
                $q->where('nm_bangsal', $filterWard);
            });
        }

        // 📋 Urutkan dan paginasi
        return $query->orderBy('tgl_masuk', 'desc')->paginate(10);
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
