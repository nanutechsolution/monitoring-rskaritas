<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PemeriksaanRanap;
use Livewire\Attributes\Computed;

class PicuCpptViewer extends Component
{
    public $noRawat;

    public function mount($noRawat)
    {
        $this->noRawat = $noRawat;
    }

    /**
     * Ambil semua data CPPT/SOAP untuk pasien ini,
     * diurutkan dari yang terbaru.
     * Kita juga eager-load nama pegawai.
     */
    #[Computed]
    public function cpptRecords()
    {
        return PemeriksaanRanap::where('no_rawat', $this->noRawat)
            ->with('pegawai') // Eager load relasi 'pegawai'
            ->orderBy('tgl_perawatan', 'desc')
            ->orderBy('jam_rawat', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.picu-cppt-viewer')->layout('layouts.app');
    }
}
