@props(['variant' => 'info'])

@php
    $map = [
        'success' => ['border-l-brand bg-surface text-ink', 'check', 'text-brand'],
        'error' => ['border-l-danger-700 bg-danger-50 text-danger-700', 'circle-alert', 'text-danger-700'],
        'warning' => ['border-l-warning-700 bg-warning-50 text-warning-700', 'triangle-alert', 'text-warning-700'],
        'info' => ['border-l-brand bg-surface text-ink', 'info', 'text-brand'],
    ];
    [$classes, $icon, $iconClass] = $map[$variant] ?? $map['info'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-md border border-line border-l-4 px-4 py-3 text-sm ' . $classes]) }} @if ($variant === 'error') role="alert" @endif>
    <x-icon :name="$icon" class="mt-0.5 size-5 shrink-0 {{ $iconClass }}" />
    <div class="min-w-0 flex-1">{{ $slot }}</div>
</div>
