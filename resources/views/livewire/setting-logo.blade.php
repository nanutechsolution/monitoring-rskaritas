<div class="bg-white p-4 rounded-lg shadow-sm max-w-sm">
    <div class="flex flex-col items-center space-y-3">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo" class="h-20 w-auto rounded">
        @else
            <span class="text-gray-400 font-semibold">Logo tidak tersedia</span>
        @endif

        <input type="file" wire:model="newLogo" class="mt-2" accept="image/*">

        <button wire:click="saveLogo"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            Simpan Logo
        </button>
    </div>

    @error('newLogo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>
