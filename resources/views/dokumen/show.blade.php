@extends('layouts.app')

@section('title', $document->judul)

@section('content')
    @php
        $qrUri = null;
        if ($document->qr_verification_url) {
            try {
                $qrUri = app(\App\Services\QrCodeService::class)->dataUri($document->qr_verification_url);
            } catch (\Throwable $e) {
                $qrUri = null;
            }
        }
    @endphp

    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
            ['label' => $document->judul],
        ]" />

        <div class="border-t-[3px] border-green-800 pt-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <h1 class="font-serif text-2xl font-semibold leading-tight text-ink">{{ $document->judul }}</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-ink-muted">
                        <span class="tnum">{{ $document->nomor_surat ?? 'Belum bernomor' }}</span>
                        <span aria-hidden="true">·</span>
                        <span>{{ $document->jenis_label }}</span>
                        <span aria-hidden="true">·</span>
                        <span class="tnum">{{ $document->created_at->translatedFormat('d F Y') }}</span>
                        <span aria-hidden="true">·</span>
                        <x-status-badge :status="$document->status" />
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if ($document->isDraft())
                        <x-button href="{{ route('dokumen.edit', [$document->jenis, $document]) }}" variant="secondary">Ubah Draf</x-button>
                        <form method="POST" action="{{ route('dokumen.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen draf ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger-ghost">Hapus</x-button>
                        </form>
                    @else
                        <x-button href="{{ route('dokumen.pdf', $document) }}">
                            <x-icon name="download" class="size-4" /> Unduh PDF
                        </x-button>
                        @if ($document->jenis === 'daftar_hadir')
                            <x-button href="{{ route('dokumen.cetak', $document) }}" variant="secondary">
                                <x-icon name="printer" class="size-4" /> Cetak
                            </x-button>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Lembar dokumen --}}
            <div class="lg:col-span-8">
                @include('dokumen._preview', ['document' => $document])
            </div>

            {{-- Informasi --}}
            <aside class="space-y-4 lg:col-span-4">
                <section class="rounded-md border border-line bg-surface p-6">
                    <h2 class="font-serif text-lg font-semibold text-ink">Informasi</h2>
                    <dl class="mt-3 divide-y divide-line text-sm">
                        <div class="py-2">
                            <dt class="text-ink-muted">Dibuat oleh</dt>
                            <dd class="mt-0.5 text-ink">{{ $document->creator->name }} <span class="tnum text-xs text-ink-muted">(NIP {{ $document->creator->nip }})</span></dd>
                        </div>
                        <div class="py-2">
                            <dt class="text-ink-muted">Dibuat pada</dt>
                            <dd class="tnum mt-0.5 text-ink">{{ $document->created_at->translatedFormat('d F Y, H:i') }} WITA</dd>
                        </div>
                        <div class="py-2">
                            <dt class="text-ink-muted">Terakhir diubah</dt>
                            <dd class="tnum mt-0.5 text-ink">{{ $document->updated_at->translatedFormat('d F Y, H:i') }} WITA</dd>
                        </div>
                        @if ($document->finalized_at)
                            <div class="py-2">
                                <dt class="text-ink-muted">Difinalisasi pada</dt>
                                <dd class="tnum mt-0.5 text-ink">{{ $document->finalized_at->translatedFormat('d F Y, H:i') }} WITA</dd>
                            </div>
                        @endif
                    </dl>
                </section>

                @if ($document->qr_verification_url)
                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-lg font-semibold text-ink">Verifikasi</h2>
                        @if ($qrUri)
                            <img src="{{ $qrUri }}" alt="QR verifikasi dokumen {{ $document->nomor_surat ?? $document->judul }}" class="mt-3 h-32 w-32 rounded-sm border border-line p-1">
                        @endif
                        <p class="mt-3 break-all text-xs text-ink-muted">{{ $document->qr_verification_url }}</p>
                        <button type="button" data-copy-url="{{ $document->qr_verification_url }}"
                            class="copy-qr-link mt-3 inline-flex h-9 items-center gap-2 rounded-md border border-control bg-surface px-3 text-sm font-medium text-ink transition-colors hover:bg-paper">
                            <x-icon name="copy" class="size-4" /> Salin tautan
                        </button>
                    </section>
                @endif
            </aside>
        </div>
    </div>
@endsection
