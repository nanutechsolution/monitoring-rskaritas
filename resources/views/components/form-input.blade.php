{{--
  Komponen ini menerima 'label' dan semua atribut input standar
  (wire:model, type, placeholder, dll)
--}}
@props([
'label' => '',
'disabled' => false
])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500'
    ]) !!}>

    {{-- Ini akan otomatis menampilkan error validasi Livewire --}}
    @error($attributes->whereStartsWith('wire:model')->first())
    <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
</div>
