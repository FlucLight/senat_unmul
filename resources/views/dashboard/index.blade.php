@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    @php
        $total = collect($stats['per_jenis'])->sum('total');
        $deskripsi = [
            'daftar_hadir' => 'Presensi peserta kegiatan resmi.',
            'undangan' => 'Pemanggilan anggota untuk rapat atau sidang.',
            'berita_acara' => 'Catatan keputusan dan hasil musyawarah.',
            'surat_pengantar' => 'Pengiriman berkas kepada Dekan atau Rektorat.',
        ];
        $drafts = $recent->where('status', \App\Models\Document::STATUS_DRAFT)->take(5);
    @endphp

    <div class="space-y-8">
        <x-breadcrumb :items="[['label' => 'Beranda']]" />

        <div class="flex flex-col gap-2 border-b border-line pb-6 sm:flex-row sm:items-end sm:justify-between">
            <h1 class="font-serif text-2xl font-semibold leading-tight text-ink">Beranda</h1>
            <p class="text-sm text-ink-muted">Selamat datang, {{ auth()->user()->name }}. {{ now()->translatedFormat('l, d F Y') }}.</p>
        </div>

        {{-- Buat dokumen --}}
        <section>
            <h2 class="font-serif text-xl font-semibold text-ink">Buat Dokumen</h2>
            <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                @foreach ($stats['per_jenis'] as $key => $item)
                    <a href="{{ route('dokumen.baru', $key) }}"
                        class="group flex min-h-16 items-center gap-4 rounded-md border border-line bg-surface px-6 py-4 transition-colors hover:bg-paper">
                        <x-icon :name="match ($key) {
                            'undangan' => 'mail',
                            'berita_acara' => 'clipboard-list',
                            'surat_pengantar' => 'send',
                            default => 'users',
                        }" class="size-5 shrink-0 text-brand" />
                        <span class="min-w-0 flex-1">
                            <span class="block text-lg font-semibold text-ink">{{ $item['label'] }}</span>
                            <span class="mt-0.5 block text-sm text-ink-muted">{{ $deskripsi[$key] ?? 'Dokumen resmi Senat FT.' }}</span>
                        </span>
                        <x-icon name="arrow-right" class="size-4 shrink-0 text-ink-muted transition-colors group-hover:text-brand" />
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Ringkasan --}}
        <section>
            <h2 class="font-serif text-xl font-semibold text-ink">Ringkasan</h2>
            <div class="mt-4 grid grid-cols-2 divide-line overflow-hidden rounded-md border border-line bg-surface sm:grid-cols-4 sm:divide-x">
                <div class="p-6">
                    <p class="tnum font-serif text-2xl font-semibold text-ink">{{ $total }}</p>
                    <p class="mt-1 text-sm text-ink-muted">Total dokumen</p>
                </div>
                <a href="{{ route('dokumen.index', ['status' => 'draft']) }}" class="p-6 transition-colors hover:bg-paper">
                    <p class="tnum font-serif text-2xl font-semibold text-warning-700">{{ $stats['draft'] }}</p>
                    <p class="mt-1 text-sm text-ink-muted">Draf</p>
                </a>
                <a href="{{ route('dokumen.index', ['status' => 'final']) }}" class="p-6 transition-colors hover:bg-paper">
                    <p class="tnum font-serif text-2xl font-semibold text-brand">{{ $stats['final'] }}</p>
                    <p class="mt-1 text-sm text-ink-muted">Final</p>
                </a>
                <div class="p-6">
                    <p class="tnum font-serif text-2xl font-semibold text-ink">{{ $stats['bulan_ini'] }}</p>
                    <p class="mt-1 text-sm text-ink-muted">Bulan ini</p>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Draf belum selesai --}}
            <section class="rounded-md border border-line bg-surface">
                <div class="flex items-center justify-between border-b border-line px-6 py-4">
                    <h2 class="font-serif text-lg font-semibold text-ink">Draf Belum Selesai</h2>
                    <a href="{{ route('dokumen.index', ['status' => 'draft']) }}" class="text-sm font-semibold text-brand hover:underline">Lihat semua</a>
                </div>

                @if ($drafts->isEmpty())
                    <div class="px-6 py-12">
                        <p class="text-sm text-ink-muted">Tidak ada draf yang menunggu diselesaikan.</p>
                        <x-button href="{{ route('dokumen.index') }}" variant="secondary" size="sm" class="mt-3">Buka Daftar Dokumen</x-button>
                    </div>
                @else
                    <ul class="divide-y divide-line">
                        @foreach ($drafts as $document)
                            <li class="flex items-center gap-3 px-6 py-3">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('dokumen.show', $document) }}" class="block truncate text-sm font-medium text-ink hover:text-brand">{{ $document->judul }}</a>
                                    <p class="mt-0.5 text-xs text-ink-muted">{{ $document->jenis_label }} · diubah {{ $document->updated_at->translatedFormat('d M Y, H:i') }}</p>
                                </div>
                                <a href="{{ route('dokumen.edit', [$document->jenis, $document]) }}" class="shrink-0 text-sm font-semibold text-brand hover:underline">Lanjutkan</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            {{-- Aktivitas terbaru --}}
            <section class="rounded-md border border-line bg-surface">
                <div class="flex items-center justify-between border-b border-line px-6 py-4">
                    <h2 class="font-serif text-lg font-semibold text-ink">Aktivitas Terbaru</h2>
                    <a href="{{ route('dokumen.index') }}" class="text-sm font-semibold text-brand hover:underline">Lihat semua</a>
                </div>

                @if ($recent->isEmpty())
                    <div class="px-6 py-12">
                        <p class="text-sm text-ink-muted">Belum ada aktivitas dokumen.</p>
                        <x-button href="{{ route('dokumen.baru', 'undangan') }}" size="sm" class="mt-3">Buat Dokumen</x-button>
                    </div>
                @else
                    <ul class="divide-y divide-line">
                        @foreach ($recent as $document)
                            <li class="flex items-center gap-3 px-6 py-3">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('dokumen.show', $document) }}" class="block truncate text-sm font-medium text-ink hover:text-brand">{{ $document->judul }}</a>
                                    <p class="mt-0.5 text-xs text-ink-muted">
                                        {{ $document->creator->name }} · {{ $document->jenis_label }} · {{ $document->updated_at->translatedFormat('d M Y, H:i') }}
                                    </p>
                                </div>
                                <x-status-badge :status="$document->status" class="shrink-0" />
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
@endsection
