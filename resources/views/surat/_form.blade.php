{{-- Form buat/edit surat. Mengharapkan variabel $jenisSurat dan $surat (opsional). --}}
<form action="{{ $surat ? route('surat.update', $surat) : route('surat.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($surat)
        @method('PUT')
    @endif
    <input type="hidden" name="jenis_surat_id" value="{{ old('jenis_surat_id', $jenisSurat->id) }}">

    <div>
        <label for="nama_surat" class="mb-1 block text-sm font-medium text-ink-700">Jenis Surat</label>
        <input type="text" id="nama_surat" value="{{ $jenisSurat->nama_surat }}" readonly
            class="w-full rounded-md border border-frame bg-surface-muted px-3 py-2.5 text-sm text-ink-700">
    </div>

    <div>
        <label for="nomor_surat" class="mb-1 block text-sm font-medium text-ink-700">Nomor Surat</label>
        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $surat?->nomor_surat) }}" required
            class="w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
        @error('nomor_surat')
            <p class="mt-1 text-xs text-error-text">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tanggal_surat" class="mb-1 block text-sm font-medium text-ink-700">Tanggal Surat</label>
        <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $surat?->tanggal_surat?->format('Y-m-d')) }}" required
            class="w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
        @error('tanggal_surat')
            <p class="mt-1 text-xs text-error-text">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="konten_surat" class="mb-1 block text-sm font-medium text-ink-700">Isi Surat</label>
        <textarea id="konten_surat" name="konten_surat" rows="12"
            class="w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-100">{{ old('konten_surat', $surat?->konten_surat ?? $jenisSurat->template_surat) }}</textarea>
        @error('konten_surat')
            <p class="mt-1 text-xs text-error-text">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-md bg-gold-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">Simpan</button>
        <a href="{{ route('surat.index') }}" class="rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 hover:bg-surface-muted">Batal</a>
    </div>
</form>

<x-tinymce-editor />
