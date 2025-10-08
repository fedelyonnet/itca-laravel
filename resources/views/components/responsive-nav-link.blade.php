@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-blue-400 bg-gray-700 focus:outline-none focus:text-blue-400 focus:bg-gray-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-300 hover:text-blue-400 hover:bg-gray-700 focus:outline-none focus:text-blue-400 focus:bg-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
