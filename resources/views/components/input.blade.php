@props(['label', 'model', 'placeholder' => ''])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input type="text" wire:model.defer="{{ $model }}" placeholder="{{ $placeholder }}"
        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
</div>
