@extends('layouts.app')

@section('title', $document->judul)

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <a href="{{ route('dokumen.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-ink-500 transition hover:text-green-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Isi
            </a>
        </div>

        <div class="overflow-hidden rounded-lg border border-frame bg-surface">
            <div class="border-b border-frame px-6 py-5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-md bg-surface-muted px-2.5 py-1 text-xs font-medium text-ink-700">{{ $document->jenis_label }}</span>
                    <x-status-badge :status="$document->status" />
                </div>
                <h1 class="mt-3 font-display text-2xl font-semibold leading-tight text-ink-900">{{ $document->judul }}</h1>
                <p class="mt-1 text-sm text-ink-400">
                    Nomor surat: <span class="tnum font-medium text-ink-700">{{ $document->nomor_surat ?? '— (belum difinalisasi)' }}</span>
                </p>
            </div>

            <dl class="divide-y divide-frame text-sm">
                <div class="grid grid-cols-3 gap-4 px-6 py-4">
                    <dt class="text-ink-400">Jenis dokumen</dt>
                    <dd class="col-span-2 font-medium text-ink-900">{{ $document->jenis_label }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-6 py-4">
                    <dt class="text-ink-400">Dibuat oleh</dt>
                    <dd class="col-span-2 font-medium text-ink-900">{{ $document->creator->name }}
                        <span class="tnum ml-1 text-xs font-normal text-ink-400">(NIP {{ $document->creator->nip }})</span>
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-6 py-4">
                    <dt class="text-ink-400">Tanggal dibuat</dt>
                    <dd class="tnum col-span-2 font-medium text-ink-900">{{ $document->created_at->translatedFormat('d M Y, H:i') }}</dd>
                </div>
                @if ($document->finalized_at)
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-ink-400">Tanggal finalisasi</dt>
                        <dd class="tnum col-span-2 font-medium text-ink-900">{{ $document->finalized_at->translatedFormat('d M Y, H:i') }}</dd>
                    </div>
                @endif
                @if ($document->qr_verification_url)
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-ink-400">Link verifikasi QR</dt>
                        <dd class="col-span-2 flex items-center gap-2">
                            <code class="tnum truncate rounded-md bg-surface-muted px-2 py-1 text-xs text-ink-700">{{ $document->qr_verification_url }}</code>
                            <button type="button" data-copy-url="{{ $document->qr_verification_url }}"
                                class="copy-qr-link shrink-0 rounded-md bg-surface-muted px-2.5 py-1.5 text-xs font-medium text-ink-700 transition hover:bg-gold-100 hover:text-gold-600">
                                Salin
                            </button>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>

        @if ($document->content)
            <div class="overflow-hidden rounded-lg border border-frame bg-surface">
                <div class="border-b border-frame px-6 py-4">
                    <h2 class="font-display text-lg font-medium text-ink-900">Isi dokumen</h2>
                </div>
                <pre class="max-h-96 overflow-auto bg-surface-muted px-6 py-4 text-xs text-ink-700"><code>{{ json_encode($document->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3">
            @if ($document->isDraft())
                <a href="{{ route('dokumen.edit', [$document->jenis, $document]) }}"
                    class="rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                    Edit draft
                </a>
                <form method="POST" action="{{ route('dokumen.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen draft ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md px-3 py-2.5 text-sm font-medium text-error-text transition hover:underline">
                        Hapus
                    </button>
                </form>
            @else
                <a href="{{ route('dokumen.pdf', $document) }}"
                    class="inline-flex items-center gap-2 rounded-md bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download PDF
                </a>
            @endif
        </div>
    </div>
@endsection