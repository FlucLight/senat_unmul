{{-- Form buat/edit surat. Mengharapkan variabel $jenisSurat dan $surat (opsional). --}}
<form action="{{ $surat ? route('surat.update', $surat) : route('surat.store') }}" method="POST">
    @csrf
    @if ($surat)
        @method('PUT')
    @endif
    <input type="hidden" name="jenis_surat_id" value="{{ old('jenis_surat_id', $jenisSurat->id) }}">

    <div class="form-group">
        <label for="nama_surat">Jenis Surat</label>
        <input type="text" class="form-control" id="nama_surat" value="{{ $jenisSurat->nama_surat }}" readonly>
    </div>
    <div class="form-group">
        <label for="nomor_surat">Nomor Surat</label>
        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
               value="{{ old('nomor_surat', $surat?->nomor_surat) }}" required>
        @error('nomor_surat')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="tanggal_surat">Tanggal Surat</label>
        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
               value="{{ old('tanggal_surat', $surat?->tanggal_surat?->format('Y-m-d')) }}" required>
        @error('tanggal_surat')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group mt-2">
        <label for="konten_surat">Isi Surat</label>
        <textarea class="form-control" id="konten_surat" name="konten_surat" rows="3">{{ old('konten_surat', $surat?->konten_surat ?? $jenisSurat->template_surat) }}</textarea>
        @error('konten_surat')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
</form>

<x-tinymce-editor />
