@props(['label', 'model', 'unit' => ''])

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-200">{{ $label }}</label>
    <div class="mt-1 relative rounded-md shadow-sm">
        <input type="text" wire:model.defer="{{ $model }}" class="block w-full pr-12 rounded-md border border-gray-700 bg-gray-900 text-gray-200 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="0">
        @if($unit)
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 text-sm">
            {{ $unit }}
        </div>
        @endif
    </div>
</div>
