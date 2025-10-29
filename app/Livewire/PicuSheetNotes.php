<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMonitoring;

class PicuSheetNotes extends Component
{
    public PicuMonitoring $monitoringSheet;

    // --- Properti untuk Text Area ---
    public $masalah;
    public $program_terapi;
    public $catatan_nutrisi;
    public $catatan_lab;

    public function mount(PicuMonitoring $monitoringSheet)
    {
        $this->monitoringSheet = $monitoringSheet;

        // Isi public properties dari data yang ada
        $this->fill($this->monitoringSheet->toArray());
    }

    /**
     * Fungsi ini akan dipanggil SETIAP KALI user
     * selesai mengedit sebuah text area (on blur).
     */
    public function saveNotes()
    {
        $dataToSave = $this->only([
            'masalah',
            'program_terapi',
            'catatan_nutrisi',
            'catatan_lab',
        ]);

        // Update data di database
        $this->monitoringSheet->update($dataToSave);

        session()->flash('success-notes', 'Catatan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.picu-sheet-notes');
    }
}
