@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman">
        <div class="flex flex-1 items-center justify-between gap-4">
            {{-- Mobile: prev/next only --}}
            <div class="flex gap-2 md:hidden">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-11 items-center rounded-md border border-line bg-surface px-4 text-sm font-medium text-ink-muted">
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-11 items-center rounded-md border border-control bg-surface px-4 text-sm font-medium text-ink transition-colors hover:bg-paper">
                        Sebelumnya
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-11 items-center rounded-md border border-control bg-surface px-4 text-sm font-medium text-ink transition-colors hover:bg-paper">
                        Berikutnya
                    </a>
                @else
                    <span class="inline-flex h-11 items-center rounded-md border border-line bg-surface px-4 text-sm font-medium text-ink-muted">
                        Berikutnya
                    </span>
                @endif
            </div>

            {{-- Desktop --}}
            <p class="hidden text-sm text-ink-muted md:block">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="tnum font-medium text-ink">{{ $paginator->firstItem() }}</span>
                    hingga
                    <span class="tnum font-medium text-ink">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span class="tnum font-medium text-ink">{{ $paginator->total() }}</span>
                hasil
            </p>

            <div class="hidden items-center gap-1 md:flex">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Halaman sebelumnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-line bg-surface text-ink-muted">
                        <x-icon name="chevron-right" class="size-4 rotate-180" />
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-control bg-surface text-ink transition-colors hover:bg-paper">
                        <x-icon name="chevron-right" class="size-4 rotate-180" />
                    </a>
                @endif

                {{-- Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="inline-flex h-9 items-center px-2 text-sm text-ink-muted">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="tnum inline-flex h-9 w-9 items-center justify-center rounded-md bg-brand text-sm font-semibold text-surface">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" aria-label="Menuju halaman {{ $page }}"
                                    class="tnum inline-flex h-9 w-9 items-center justify-center rounded-md border border-line bg-surface text-sm font-medium text-ink transition-colors hover:bg-paper">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-control bg-surface text-ink transition-colors hover:bg-paper">
                        <x-icon name="chevron-right" class="size-4" />
                    </a>
                @else
                    <span aria-disabled="true" aria-label="Halaman berikutnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-line bg-surface text-ink-muted">
                        <x-icon name="chevron-right" class="size-4" />
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
