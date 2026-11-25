@props(['status'])

@php
    $map = [
        'final' => [
            'class' => 'border-green-200 bg-brand-subtle text-brand-hover',
            'icon' => 'file-check',
            'label' => 'Final',
        ],
        'draft' => [
            'class' => 'border-line-strong bg-paper-200 text-ink',
            'icon' => 'pencil-line',
            'label' => 'Draf',
        ],
        'dibatalkan' => [
            'class' => 'border-danger-200 bg-danger-50 text-danger-700',
            'icon' => 'ban',
            'label' => 'Dibatalkan',
        ],
        'aktif' => [
            'class' => 'border-green-200 bg-brand-subtle text-brand-hover',
            'icon' => 'check',
            'label' => 'Aktif',
        ],
        'nonaktif' => [
            'class' => 'border-line-strong bg-paper-200 text-ink-muted',
            'icon' => 'ban',
            'label' => 'Nonaktif',
        ],
    ];

    $item = $map[$status] ?? [
        'class' => 'border-line-strong bg-paper-200 text-ink-muted',
        'icon' => 'info',
        'label' => ucfirst($status),
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-sm border px-2 py-1 text-xs font-medium ' . $item['class']]) }}>
    <x-icon :name="$item['icon']" class="size-3.5 shrink-0" />
    {{ $item['label'] }}
</span>
