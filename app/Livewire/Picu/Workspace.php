<?php

namespace App\Livewire\Picu;

use Livewire\Component;

class Workspace extends Component
{
    public $noRawat; 
    public $sheetDate; 

    public $noRawatAsli;

    /**
     * Mount component, menerima parameter dari Rute.
     */
    public function mount($noRawat, $sheetDate = null)
    {
        $this->noRawat = $noRawat;
        $this->sheetDate = $sheetDate;
        // Kembalikan underscore ke slash untuk query database
        $this->noRawatAsli = str_replace('_', '/', $this->noRawat);
    }

    public function render()
    {
        // Beri tahu Livewire untuk menggunakan layout utama aplikasi Anda
        // Ganti 'layouts.app' jika nama file layout Anda berbeda
        return view('livewire.picu.workspace')
            ->layout('layouts.app');
    }
}
