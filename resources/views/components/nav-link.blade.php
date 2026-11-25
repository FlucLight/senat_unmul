@props(['href', 'active' => false])

@php
    $classes = $active
        ? 'inline-flex h-16 items-center border-b-2 border-brand px-3 text-sm font-semibold text-brand-hover'
        : 'inline-flex h-16 items-center border-b-2 border-transparent px-3 text-sm font-medium text-ink transition-colors hover:border-line-strong hover:bg-paper';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if ($active) aria-current="page" @endif>
    <span class="truncate">{{ $slot }}</span>
</a>
