<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingLogo extends Component
{
    use WithFileUploads;

    public $logoBase64;
    public $newLogo; // untuk upload baru

    public function mount()
    {
        $this->loadLogo();
    }

    public function loadLogo()
    {
        $setting = DB::table('setting')->first();
        if ($setting && $setting->logo) {
            $this->logoBase64 = 'data:image/png;base64,' . base64_encode($setting->logo);
        } else {
            $this->logoBase64 = null;
        }
    }

    public function saveLogo()
    {
        if (!$this->newLogo) return;

        $data = file_get_contents($this->newLogo->getRealPath());
        DB::table('setting')->update(['logo' => $data]);

        $this->loadLogo(); // reload logoBase64
        $this->emit('logo-updated');
    }

    public function render()
    {
        return view('livewire.setting-logo');
    }
}
