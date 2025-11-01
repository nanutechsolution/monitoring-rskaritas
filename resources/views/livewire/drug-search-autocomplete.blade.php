<div class="relative">
    {{-- Input teks ini ada DI DALAM file child component --}}
    <input type="text" class="w-full text-sm rounded-md border-gray-300 shadow-sm" placeholder="Ketik nama obat..." wire:model.live.debounce.300ms="query" wire:keydown.escape="isOpen = false" wire:focus="triggerSearch">

    {{-- Dropdown Hasil --}}
    @if($isOpen && count($results) > 0)
    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
        <ul class="max-h-60 overflow-y-auto">
            @foreach($results as $i => $result)
            <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100" wire:click.prevent="selectDrug('{{ $result->nm_obat }}')">
                    {{ $result->nm_obat }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    {{-- @elseif(strlen($query) >= 2 && count($results) == 0)
    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg p-3 text-sm text-gray-500">
        Obat tidak ditemukan.
    </div> --}}
    @endif
</div>
