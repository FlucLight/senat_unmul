{{-- Form buat/edit surat. Mengharapkan variabel $jenisSurat dan $surat (opsional). --}}
@php
    $input = 'mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
    $label = 'block text-sm font-medium text-ink';
@endphp

<form action="{{ $surat ? route('surat.update', $surat) : route('surat.store') }}" method="POST" class="space-y-6">
    @csrf
    @if ($surat)
        @method('PUT')
    @endif
    <input type="hidden" name="jenis_surat_id" value="{{ old('jenis_surat_id', $jenisSurat->id) }}">

    <div>
        <label for="nama_surat" class="{{ $label }}">Jenis Surat</label>
        <input type="text" id="nama_surat" value="{{ $jenisSurat->nama_surat }}" readonly
            class="mt-1 h-10 w-full rounded-md border border-line bg-paper-200 px-3 text-base text-ink">
    </div>

    <div>
        <label for="nomor_surat" class="{{ $label }}">Nomor Surat <span class="font-normal text-ink-muted">(wajib)</span></label>
        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $surat?->nomor_surat) }}" required
            class="tnum {{ $input }}">
        @error('nomor_surat')
            <p class="mt-1 text-xs text-danger-700">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tanggal_surat" class="{{ $label }}">Tanggal Surat <span class="font-normal text-ink-muted">(wajib)</span></label>
        <input type="date" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $surat?->tanggal_surat?->format('Y-m-d')) }}" required
            class="tnum {{ $input }}">
        @error('tanggal_surat')
            <p class="mt-1 text-xs text-danger-700">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="konten_surat" class="{{ $label }}">Isi Surat</label>
        <textarea id="konten_surat" name="konten_surat" rows="12"
            class="mt-1 w-full rounded-md border border-control bg-surface px-3 py-2 text-base text-ink focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">{{ old('konten_surat', $surat?->konten_surat ?? $jenisSurat->template_surat) }}</textarea>
        @error('konten_surat')
            <p class="mt-1 text-xs text-danger-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-2 border-t border-line pt-4">
        <x-button type="submit">Simpan</x-button>
        <x-button href="{{ route('surat.index') }}" variant="secondary">Batal</x-button>
    </div>
</form>

<x-tinymce-editor />
