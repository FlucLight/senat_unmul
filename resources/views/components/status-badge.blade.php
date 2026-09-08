@props(['status'])

@if ($status === 'final')
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-md bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700']) }}>
        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        Final
    </span>
@elseif ($status === 'draft')
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-md bg-gold-100 px-2.5 py-1 text-xs font-medium text-draft-text']) }}>
        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        Draft
    </span>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md bg-surface-muted px-2.5 py-1 text-xs font-medium text-ink-500']) }}>{{ $status }}</span>
@endif