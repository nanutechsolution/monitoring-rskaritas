<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MonitoringIdentitas extends Component
{
    public $nama_bayi;
    public $nama_ibu;
    public $tanggal_lahir;
    public $umur_kehamilan;
    public $bb_lahir;
    public $diagnosa;
    public $dokter;
    public $ruangan;
    public $register;

    public function simpan()
    {
        $this->validate([
            'nama_bayi' => 'required',
        ]);

        session()->flash('success', 'Data identitas berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.monitoring-identitas');
    }
}
