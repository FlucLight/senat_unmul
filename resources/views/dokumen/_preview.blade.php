@php
    $content = (array) ($document->content ?? []);

    $fields = match ($document->jenis) {
        'undangan' => [
            'Perihal' => $content['perihal'] ?? $document->judul,
            'Tanggal surat' => !empty($content['tanggal_surat']) ? \Carbon\Carbon::parse($content['tanggal_surat'])->translatedFormat('d F Y') : null,
            'Hari/tanggal acara' => !empty($content['tanggal_acara']) ? \Carbon\Carbon::parse($content['tanggal_acara'])->translatedFormat('l, d F Y') : null,
            'Waktu' => trim(($content['waktu_mulai'] ?? '') . '–' . ($content['waktu_selesai'] ?? ''), '–') ?: null,
            'Tempat' => $content['tempat'] ?? null,
            'Pemimpin rapat' => $content['pemimpin_rapat'] ?? null,
            'Agenda' => $content['agenda'] ?? null,
        ],
        'berita_acara' => [
            'Perihal' => $content['perihal'] ?? $document->judul,
            'Tanggal rapat' => !empty($content['tanggal_rapat']) ? \Carbon\Carbon::parse($content['tanggal_rapat'])->translatedFormat('l, d F Y') : null,
            'Tempat' => $content['tempat'] ?? null,
            'Pemimpin rapat' => $content['pemimpin_rapat'] ?? null,
            'Pembahasan' => $content['pembahasan'] ?? null,
            'Keputusan' => $content['keputusan'] ?? null,
        ],
        'surat_pengantar' => [
            'Perihal' => $content['perihal'] ?? $document->judul,
            'Tanggal surat' => !empty($content['tanggal_surat']) ? \Carbon\Carbon::parse($content['tanggal_surat'])->translatedFormat('d F Y') : null,
            'Tujuan' => $content['tujuan'] ?? null,
            'Isi ringkas' => $content['isi_ringkas'] ?? null,
            'Penandatangan' => $content['penandatangan'] ?? null,
        ],
        'daftar_hadir' => [
            'Nama acara' => $content['nama_acara'] ?? $document->judul,
            'Hari/tanggal' => !empty($content['tanggal']) ? \Carbon\Carbon::parse($content['tanggal'])->translatedFormat('l, d F Y') : null,
            'Waktu' => trim(($content['waktu_mulai'] ?? '') . '–' . ($content['waktu_selesai'] ?? ''), '–') ?: null,
            'Tempat' => $content['tempat'] ?? null,
            'Penyelenggara' => $content['penyelenggara'] ?? null,
        ],
        default => ['Perihal' => $document->judul],
    };

    $peserta = $content['peserta'] ?? $content['undangan'] ?? [];
@endphp

<div class="relative rounded-md border border-line bg-surface p-6 sm:p-8">
    @if ($document->status !== 'final')
        <span class="pointer-events-none absolute inset-0 flex items-start justify-center pt-24 font-serif text-display font-semibold tracking-widest text-ink/10">
            {{ $document->status === 'draft' ? 'DRAF' : 'DIBATALKAN' }}
        </span>
    @endif

    <div class="relative">
        <div class="flex items-center gap-4 border-b border-line pb-4">
            <img src="{{ asset('image.png') }}" alt="" class="h-12 w-auto object-contain">
            <div class="min-w-0">
                <p class="font-serif text-sm font-semibold text-ink">Senat Fakultas Teknik</p>
                <p class="text-xs text-ink-muted">Universitas Mulawarman</p>
            </div>
        </div>
        <div class="h-0.5 bg-gold-600"></div>

        <div class="mx-auto max-w-prose py-6">
            <h2 class="text-center font-serif text-xl font-semibold leading-snug text-ink">{{ $document->judul }}</h2>
            <p class="tnum mt-1 text-center text-sm text-ink-muted">{{ $document->nomor_surat ?? 'Nomor diberikan saat finalisasi' }}</p>

            <dl class="mt-6 space-y-3 text-sm">
                @foreach ($fields as $label => $value)
                    @if ($value)
                        <div class="grid grid-cols-3 gap-3">
                            <dt class="text-ink-muted">{{ $label }}</dt>
                            <dd class="col-span-2 whitespace-pre-line text-ink">{{ $value }}</dd>
                        </div>
                    @endif
                @endforeach
            </dl>

            @if (!empty($peserta))
                <div class="mt-6">
                    <p class="text-sm font-semibold text-ink">{{ $document->jenis === 'undangan' ? 'Penerima undangan' : 'Peserta' }}</p>
                    <ul class="mt-2 divide-y divide-line border-y border-line">
                        @foreach ($peserta as $i => $orang)
                            <li class="flex items-start gap-3 py-2 text-sm">
                                <span class="tnum w-6 shrink-0 text-ink-muted">{{ $i + 1 }}.</span>
                                <span class="text-ink">
                                    {{ $orang['nama'] ?? '—' }}
                                    @if (!empty($orang['jabatan_instansi']))
                                        <span class="block text-xs text-ink-muted">{{ $orang['jabatan_instansi'] }}</span>
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-12 flex justify-end">
                <div class="text-center text-sm">
                    <p class="text-ink-muted">Samarinda, {{ $document->finalized_at?->translatedFormat('d F Y') ?? $document->created_at->translatedFormat('d F Y') }}</p>
                    <p class="mt-1 text-ink-muted">Pemimpin rapat</p>
                    <div class="mt-12 border-t border-line pt-1 text-ink">{{ $content['pemimpin_rapat'] ?? $content['penandatangan'] ?? '..............................' }}</div>
                </div>
            </div>
        </div>

        <p class="border-t border-line pt-3 text-center text-xs text-ink-muted">
            Dokumen ini diterbitkan melalui Sistem Manajemen dan Validasi Dokumen Senat FT Unmul.
        </p>
    </div>
</div>
