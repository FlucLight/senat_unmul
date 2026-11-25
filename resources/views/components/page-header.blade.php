@props(['title', 'description' => null])

<div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        <h1 class="font-serif text-2xl font-semibold leading-tight text-ink">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-prose text-sm text-ink-muted">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
