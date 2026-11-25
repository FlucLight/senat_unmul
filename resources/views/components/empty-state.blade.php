@props(['icon' => 'file-text', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'rounded-md border border-line bg-surface p-8']) }}>
    <x-icon :name="$icon" class="size-6 text-ink-muted" />
    <h3 class="mt-3 text-lg font-semibold text-ink">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1 max-w-prose text-sm text-ink-muted">{{ $description }}</p>
    @endif
    @isset($slot)
        <div class="mt-4">{{ $slot }}</div>
    @endisset
</div>
