@php
    $defaults = config('senatitor.margins');
@endphp

<div class="card mt-3 mb-3">
    <div class="card-header">Pengaturan Kertas &amp; Logo</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="ukuran_kertas" class="form-label">Ukuran Kertas</label>
                <select class="form-select" id="ukuran_kertas" name="ukuran_kertas" required>
                    @foreach (config('senatitor.paper_sizes') as $value => $label)
                        <option value="{{ $value }}" @selected(old('ukuran_kertas', $jenis_surat->ukuran_kertas ?? 'a4') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('ukuran_kertas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="logo" class="form-label">Logo Kop Surat (opsional)</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                <div class="form-text">Kosongkan untuk memakai logo default. Format: JPG/PNG, maks. 2 MB.</div>
                @error('logo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                @if (!empty($jenis_surat?->logo))
                    <div class="mt-2">
                        <img src="{{ asset($jenis_surat->logo) }}" alt="Logo saat ini" style="height: 60px; width: auto;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" value="1" id="hapus_logo" name="hapus_logo">
                            <label class="form-check-label" for="hapus_logo">Hapus logo custom dan gunakan logo default</label>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <label class="form-label">Margin Kertas (cm)</label>
        <div class="row">
            <div class="col-6 col-md-3 mb-2">
                <label for="margin_atas" class="form-label">Atas</label>
                <input type="number" step="0.1" min="0" max="10" class="form-control" id="margin_atas" name="margin_atas"
                       value="{{ old('margin_atas', $jenis_surat->margin_atas ?? $defaults['top']) }}" required>
                @error('margin_atas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3 mb-2">
                <label for="margin_bawah" class="form-label">Bawah</label>
                <input type="number" step="0.1" min="0" max="10" class="form-control" id="margin_bawah" name="margin_bawah"
                       value="{{ old('margin_bawah', $jenis_surat->margin_bawah ?? $defaults['bottom']) }}" required>
                @error('margin_bawah')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3 mb-2">
                <label for="margin_kiri" class="form-label">Kiri</label>
                <input type="number" step="0.1" min="0" max="10" class="form-control" id="margin_kiri" name="margin_kiri"
                       value="{{ old('margin_kiri', $jenis_surat->margin_kiri ?? $defaults['left']) }}" required>
                @error('margin_kiri')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3 mb-2">
                <label for="margin_kanan" class="form-label">Kanan</label>
                <input type="number" step="0.1" min="0" max="10" class="form-control" id="margin_kanan" name="margin_kanan"
                       value="{{ old('margin_kanan', $jenis_surat->margin_kanan ?? $defaults['right']) }}" required>
                @error('margin_kanan')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr>
        <label class="form-label">Halaman Lanjutan (Otomatis)</label>
        <div class="alert alert-info py-2 mb-2">
            <div><strong>Kop surat</strong> otomatis ditampilkan di setiap halaman baru saat PDF diekspor.</div>
            <div class="mt-1"><strong>Footer/tanda tangan</strong> otomatis diambil dari tabel paling bawah (contoh: Samarinda, [[TANGGAL_SURAT]] sampai NIP) dan ditempatkan di bagian bawah halaman. Nomor NIP dikosongkan otomatis.</div>
        </div>

        <input type="hidden" name="ulang_kop" value="1">

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" id="ulang_footer" name="ulang_footer"
                   @checked(old('ulang_footer', $jenis_surat->ulang_footer ?? false))>
            <label class="form-check-label" for="ulang_footer">
                Gunakan footer kustom (ganti footer otomatis)
            </label>
        </div>
        <div class="mb-2">
            <label for="footer_surat" class="form-label">Isi Footer Kustom (opsional)</label>
            <textarea class="form-control" id="footer_surat" name="footer_surat" rows="2"
                      placeholder="Kosongkan untuk memakai tabel tanda tangan paling bawah secara otomatis">{{ old('footer_surat', $jenis_surat->footer_surat ?? '') }}</textarea>
            <div class="form-text">Jika diisi dan opsi di atas aktif, footer ini yang dipakai. Jika tidak, sistem otomatis memakai tabel paling bawah sebagai footer. Mendukung kode isian.</div>
            @error('footer_surat')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
