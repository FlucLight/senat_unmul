@props(['href', 'active' => false])

@php
    $classes = $active
        ? 'inline-flex items-center h-[60px] border-b-2 border-green-700 px-3 text-sm font-medium text-green-700'
        : 'inline-flex items-center h-[60px] border-b-2 border-transparent px-3 text-sm font-medium text-ink-700 hover:text-ink-900 hover:border-frame-strong transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    <span class="truncate">{{ $slot }}</span>
</a>
