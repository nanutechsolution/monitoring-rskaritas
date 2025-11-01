<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObatBhpOk;

class DrugSearchAutocomplete extends Component
{
    public $query = '';
    public $results = [];
    public $isOpen = false;
    public $index;

    public function mount($index, $initialValue = '')
    {
        $this->index = $index;
        $this->query = $initialValue;
    }

    /**
     * 💡 BARU: Metode helper private untuk logika pencarian
     */
    private function performSearch()
    {
        if (strlen($this->query) < 2) {
            $this->isOpen = false;
            $this->results = [];
            return;
        }

        $this->results = ObatBhpOk::where('nm_obat', 'like', '%' . $this->query . '%')
            ->limit(7)
            ->get();

        $this->isOpen = true;
    }

    /**
     * 1. Metode 'magic' (lifecycle)
     * Dipanggil otomatis oleh wire:model.live
     */
    public function updatedQuery()
    {
        $this->performSearch(); // Memanggil helper
    }

    /**
     * 💡 BARU: 2. Metode 'aman' (publik)
     * Aman untuk dipanggil dari frontend (wire:focus)
     */
    public function triggerSearch()
    {
        $this->performSearch(); // Memanggil helper yang sama
    }

    /**
     * Dipanggil saat pengguna mengklik hasil pencarian
     */
    public function selectDrug($drugName)
    {
        $this->query = $drugName;
        $this->isOpen = false;
        $this->results = [];

        // Kirim event ke komponen induk
        $this->dispatch('drugSelected', index: $this->index, name: $drugName);
    }

    public function render()
    {
        return view('livewire.drug-search-autocomplete')->layout('layouts.app');
    }
}
