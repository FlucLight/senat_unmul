@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-md font-semibold transition-colors disabled:cursor-not-allowed disabled:bg-line-strong disabled:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus';

    $variants = [
        'primary' => 'bg-brand text-surface hover:bg-brand-hover active:bg-brand-active',
        'secondary' => 'border border-control bg-surface text-ink hover:bg-paper',
        'ghost' => 'text-brand hover:bg-brand-subtle',
        'danger' => 'bg-danger-700 text-surface hover:opacity-90',
        'danger-ghost' => 'text-danger-700 hover:bg-danger-50',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-xs pointer-coarse:h-11',
        'md' => 'h-10 px-4 text-sm pointer-coarse:h-11',
        'lg' => 'h-12 px-6 text-base pointer-coarse:h-12',
        'icon' => 'h-8 w-8 pointer-coarse:h-11 pointer-coarse:w-11',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
