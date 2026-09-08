@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman">
        <div class="flex flex-1 items-center justify-between gap-4">
            {{-- Mobile: prev/next only --}}
            <div class="flex gap-2 md:hidden">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center rounded-md border border-frame bg-surface px-4 py-2 text-sm font-medium text-ink-400">
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-md border border-frame-strong bg-surface px-4 py-2 text-sm font-medium text-ink-900 transition hover:bg-gold-50">
                        Sebelumnya
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-md border border-frame-strong bg-surface px-4 py-2 text-sm font-medium text-ink-900 transition hover:bg-gold-50">
                        Berikutnya
                    </a>
                @else
                    <span class="inline-flex items-center rounded-md border border-frame bg-surface px-4 py-2 text-sm font-medium text-ink-400">
                        Berikutnya
                    </span>
                @endif
            </div>

            {{-- Desktop --}}
            <p class="hidden text-sm text-ink-400 md:block">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-medium text-ink-900">{{ $paginator->firstItem() }}</span>
                    hingga
                    <span class="font-medium text-ink-900">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span class="font-medium text-ink-900">{{ $paginator->total() }}</span>
                hasil
            </p>

            <div class="hidden items-center gap-1.5 md:flex">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Halaman sebelumnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-frame bg-surface text-ink-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-frame-strong bg-surface text-ink-700 transition hover:bg-gold-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                @endif

                {{-- Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="inline-flex h-9 items-center px-2 text-sm text-ink-400">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="tnum inline-flex h-9 w-9 items-center justify-center rounded-md bg-gold-600 text-sm font-semibold text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" aria-label="Menuju halaman {{ $page }}"
                                    class="tnum inline-flex h-9 w-9 items-center justify-center rounded-md border border-frame bg-surface text-sm font-medium text-ink-700 transition hover:bg-gold-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-frame-strong bg-surface text-ink-700 transition hover:bg-gold-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="Halaman berikutnya"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-frame bg-surface text-ink-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </nav>
@endif