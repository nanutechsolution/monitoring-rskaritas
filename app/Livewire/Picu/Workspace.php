<?php

namespace App\Livewire\Picu;

use Livewire\Component;

class Workspace extends Component
{
    public $noRawat; // Ini akan berisi '2025_10_30_000001'
    public $sheetDate; // Ini opsional, bisa null

    public $noRawatAsli; // Ini untuk '2025/10/30/000001'

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
