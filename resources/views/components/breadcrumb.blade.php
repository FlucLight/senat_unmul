@props(['items' => []])

<nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
    <ol class="flex flex-wrap items-center gap-2">
        @foreach ($items as $item)
            @if (!$loop->first)
                <li aria-hidden="true"><x-icon name="chevron-right" class="size-3.5" /></li>
            @endif

            @if (!empty($item['url']) && !$loop->last)
                <li><a href="{{ $item['url'] }}" class="text-brand underline-offset-2 hover:underline">{{ $item['label'] }}</a></li>
            @else
                <li @if ($loop->last) aria-current="page" @endif class="{{ $loop->last ? 'text-ink' : '' }}">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
