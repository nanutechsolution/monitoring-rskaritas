@props(['active'])

@php
// Logika ini sekarang memiliki warna teks baru
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2
               border-white dark:border-primary-400
               text-sm font-medium leading-5 text-white dark:text-primary-300
               focus:outline-none focus:border-primary-100 dark:focus:border-primary-500
               transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2
               border-transparent
               text-sm font-medium leading-5 text-primary-200 dark:text-gray-400
               hover:text-white dark:hover:text-gray-300
               hover:border-primary-100 dark:hover:border-gray-300
               focus:outline-none focus:text-white dark:focus:text-gray-300
               focus:border-primary-100 dark:focus:border-gray-300
               transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
