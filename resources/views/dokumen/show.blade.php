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

        @if ($document->jenis === 'daftar_hadir' && is_array($document->content))
            @php $content = $document->content; @endphp
            {{-- Detail Acara Daftar Hadir --}}
            <div class="overflow-hidden rounded-lg border border-frame bg-surface p-6 space-y-4">
                <h2 class="font-display text-lg font-medium text-ink-900">Rincian acara</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-ink-400">Hari / Tanggal:</span>
                        <p class="tnum font-medium text-ink-900 mt-0.5">
                            {{ !empty($content['tanggal']) ? \Carbon\Carbon::parse($content['tanggal'])->translatedFormat('l, d F Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-ink-400">Waktu:</span>
                        <p class="tnum font-medium text-ink-900 mt-0.5">
                            {{ $content['waktu_mulai'] ?? '—' }} s/d {{ $content['waktu_selesai'] ?? '—' }} WITA
                        </p>
                    </div>
                    <div>
                        <span class="text-ink-400">Tempat:</span>
                        <p class="font-medium text-ink-900 mt-0.5">{{ $content['tempat'] ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-ink-400">Penyelenggara:</span>
                        <p class="font-medium text-ink-900 mt-0.5">{{ $content['penyelenggara'] ?? '—' }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-frame">
                    <h3 class="font-display text-sm font-semibold text-ink-900 mb-3">
                        Daftar Peserta ({{ count($content['peserta'] ?? []) }} orang)
                    </h3>
                    <div class="overflow-hidden rounded border border-frame">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-surface-muted font-medium text-ink-700">
                                <tr>
                                    <th class="w-12 px-3 py-2 text-center">No.</th>
                                    <th class="px-3 py-2">Nama</th>
                                    <th class="px-3 py-2">Jabatan / Instansi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-frame">
                                @forelse ($content['peserta'] ?? [] as $idx => $p)
                                    <tr class="hover:bg-gold-50/40">
                                        <td class="tnum px-3 py-2 text-center text-ink-400">{{ $idx + 1 }}</td>
                                        <td class="px-3 py-2 font-medium text-ink-900">{{ $p['nama'] ?? '—' }}</td>
                                        <td class="px-3 py-2 text-ink-700">{{ $p['jabatan_instansi'] ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-4 text-center text-ink-400 italic">Belum ada data peserta.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif ($document->content)
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

                @if ($document->jenis === 'daftar_hadir')
                    <a href="{{ route('dokumen.cetak', $document) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 rounded-md border border-frame-strong bg-surface px-4 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-surface-muted">
                        <svg class="h-4 w-4 text-ink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656h10.5Z" />
                        </svg>
                        Cetak / Tanda Tangan
                    </a>
                @endif
            @endif
        </div>
    </div>
@endsection
