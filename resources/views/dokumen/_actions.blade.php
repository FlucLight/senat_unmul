@php($document = $document ?? null)

<div class="flex items-center justify-end gap-1">
    <a href="{{ route('dokumen.show', $document) }}" aria-label="Lihat detail dokumen" title="Lihat detail" class="icon-action">
        <x-icon name="file-text" class="size-4" />
    </a>

    @if ($document->isDraft())
        <a href="{{ route('dokumen.edit', [$document->jenis, $document]) }}" aria-label="Ubah draf" title="Ubah draf" class="icon-action">
            <x-icon name="pencil-line" class="size-4" />
        </a>
        <form method="POST" action="{{ route('dokumen.destroy', $document) }}"
            data-confirm="Hapus dokumen draf ini? Tindakan ini tidak dapat dibatalkan."
            data-confirm-title="Hapus Dokumen Draf?"
            data-confirm-ok="Hapus"
            data-confirm-variant="danger">
            @csrf
            @method('DELETE')
            <button type="submit" aria-label="Hapus draf" title="Hapus draf"
                class="inline-flex h-8 w-8 items-center justify-center rounded-sm text-ink-muted transition-colors hover:bg-danger-50 hover:text-danger-700">
                <x-icon name="trash-2" class="size-4" />
            </button>
        </form>
    @else
        <a href="{{ route('dokumen.pdf', $document) }}" aria-label="Unduh PDF" title="Unduh PDF" class="icon-action">
            <x-icon name="download" class="size-4" />
        </a>
        @if ($document->qr_verification_url)
            <button type="button" aria-label="Salin tautan verifikasi" title="Salin tautan verifikasi"
                data-copy-url="{{ $document->qr_verification_url }}"
                class="copy-qr-link icon-action">
                <x-icon name="copy" class="size-4" />
            </button>
        @endif
    @endif
</div>
