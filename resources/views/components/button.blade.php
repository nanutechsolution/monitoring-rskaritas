@props(['type' => 'button', 'color' => 'teal', 'icon' => null])

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white bg-$color-600 hover:bg-$color-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-$color-500"]) }}>
    @if($icon)
        <x-dynamic-component :component="$icon" class="w-4 h-4 mr-2" />
    @endif
    {{ $slot }}
</button>
