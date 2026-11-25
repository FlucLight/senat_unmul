<div class="relative rounded-md border border-line bg-surface p-6 sm:p-8">
    @if ($document->status !== 'final')
        <span class="pointer-events-none absolute inset-0 flex items-start justify-center pt-24 font-serif text-display font-semibold tracking-widest text-ink/10">
            {{ $document->status === 'draft' ? 'DRAF' : 'DIBATALKAN' }}
        </span>
    @endif

    <div class="relative">
        <div class="flex items-center gap-4 border-b border-line pb-4">
            <img src="{{ asset('image.png') }}" alt="" class="h-12 w-auto object-contain">
            <div class="min-w-0 flex-1 text-center">
                <p class="text-[11px] font-bold uppercase text-ink">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                <p class="text-[13px] font-bold uppercase text-ink">Universitas Mulawarman</p>
                <p class="text-[14px] font-bold uppercase text-ink">Fakultas Teknik — Senat Fakultas</p>
                <p class="text-[9px] text-ink-muted">Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119</p>
            </div>
            <div class="w-12"></div>
        </div>
        <div class="h-0.5 bg-gold-600"></div>

        <div class="mx-auto max-w-prose py-6">
            <div class="document-body text-sm leading-relaxed text-ink" style="font-family:'Times New Roman',Times,serif; font-size:12pt; line-height:1.5;">
                {!! $bodyHtml !!}
            </div>
        </div>

        <p class="border-t border-line pt-3 text-center text-xs text-ink-muted">
            Dokumen ini diterbitkan melalui Sistem Manajemen dan Validasi Dokumen Senat FT Unmul.
        </p>
    </div>
</div>
