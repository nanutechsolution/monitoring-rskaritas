@props([
    'label' => '',
    'disabled' => false
])

<div class="flex items-center">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'type' => 'checkbox',
        'class' => 'h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500'
    ]) !!}>
    <label for="{{ $attributes->get('id') }}" class="ml-2 block text-sm text-gray-900">{{ $label }}</label>
</div>
