@extends('layouts.app')

@section('title', $document->judul)

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <a href="{{ route('dokumen.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-blue-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Isi
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">{{ $document->jenis_label }}</span>
                    @if ($document->isFinal())
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Final</span>
                    @else
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">Draft</span>
                    @endif
                </div>
                <h1 class="mt-3 text-2xl font-bold text-slate-900">{{ $document->judul }}</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Nomor surat: <span class="font-medium text-slate-700">{{ $document->nomor_surat ?? '— (belum difinalisasi)' }}</span>
                </p>
            </div>

            <dl class="divide-y divide-slate-100 text-sm">
                <div class="grid grid-cols-3 gap-4 px-6 py-4">
                    <dt class="text-slate-500">Jenis dokumen</dt>
                    <dd class="col-span-2 font-medium text-slate-900">{{ $document->jenis_label }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-6 py-4">
                    <dt class="text-slate-500">Dibuat oleh</dt>
                    <dd class="col-span-2 font-medium text-slate-900">{{ $document->creator->name }}
                        <span class="ml-1 text-xs font-normal text-slate-400">(NIP {{ $document->creator->nip }})</span>
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-6 py-4">
                    <dt class="text-slate-500">Tanggal dibuat</dt>
                    <dd class="col-span-2 font-medium text-slate-900">{{ $document->created_at->translatedFormat('d M Y, H:i') }}</dd>
                </div>
                @if ($document->finalized_at)
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-slate-500">Tanggal finalisasi</dt>
                        <dd class="col-span-2 font-medium text-slate-900">{{ $document->finalized_at->translatedFormat('d M Y, H:i') }}</dd>
                    </div>
                @endif
                @if ($document->qr_verification_url)
                    <div class="grid grid-cols-3 gap-4 px-6 py-4">
                        <dt class="text-slate-500">Link verifikasi QR</dt>
                        <dd class="col-span-2 flex items-center gap-2">
                            <code class="truncate rounded bg-slate-100 px-2 py-1 text-xs text-slate-700">{{ $document->qr_verification_url }}</code>
                            <button type="button" data-copy-url="{{ $document->qr_verification_url }}"
                                class="copy-qr-link shrink-0 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200">
                                Salin
                            </button>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>

        @if ($document->content)
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">Isi dokumen</h2>
                </div>
                <pre class="max-h-96 overflow-auto bg-slate-50 px-6 py-4 text-xs text-slate-600"><code>{{ json_encode($document->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3">
            @if ($document->isDraft())
                <a href="{{ route('dokumen.edit', [$document->jenis, $document]) }}"
                    class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    Edit draft
                </a>
                <form method="POST" action="{{ route('dokumen.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen draft ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            @else
                <a href="{{ route('dokumen.pdf', $document) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download PDF
                </a>
            @endif
        </div>
    </div>
@endsection