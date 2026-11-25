@php
    $defaults = config('senatitor.margins');
    $input = 'mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
    $label = 'block text-sm font-medium text-ink';
@endphp

<section class="space-y-6 rounded-md border border-line bg-paper-200 p-6">
    <h2 class="font-serif text-lg font-semibold text-ink">Pengaturan Kertas dan Logo</h2>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="ukuran_kertas" class="{{ $label }}">Ukuran Kertas</label>
            <select id="ukuran_kertas" name="ukuran_kertas" required class="{{ $input }}">
                @foreach (config('senatitor.paper_sizes') as $value => $lbl)
                    <option value="{{ $value }}" @selected(old('ukuran_kertas', $jenis_surat->ukuran_kertas ?? 'a4') === $value)>{{ $lbl }}</option>
                @endforeach
            </select>
            @error('ukuran_kertas')<p class="mt-1 text-xs text-danger-700">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="logo" class="{{ $label }}">Logo Kop Surat <span class="font-normal text-ink-muted">(opsional)</span></label>
            <input type="file" id="logo" name="logo" accept="image/*" class="mt-1 block w-full text-sm text-ink file:mr-3 file:rounded-sm file:border file:border-control file:bg-surface file:px-3 file:py-2 file:text-sm file:font-medium file:text-ink hover:file:bg-paper">
            <p class="mt-1 text-xs text-ink-muted">Kosongkan untuk logo default. JPG/PNG, maks. 2 MB.</p>
            @error('logo')<p class="mt-1 text-xs text-danger-700">{{ $message }}</p>@enderror
            @if (!empty($jenis_surat?->logo))
                <div class="mt-3 flex items-center gap-3">
                    <img src="{{ asset($jenis_surat->logo) }}" alt="Logo saat ini" class="h-12 w-auto rounded-sm border border-line bg-surface p-1">
                    <label class="flex items-center gap-2 text-xs text-ink">
                        <input type="checkbox" value="1" id="hapus_logo" name="hapus_logo" class="size-4 rounded-sm border border-control accent-brand">
                        Hapus logo custom
                    </label>
                </div>
            @endif
        </div>
    </div>

    <div>
        <p class="{{ $label }}">Margin Kertas (cm)</p>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach (['atas' => 'top', 'bawah' => 'bottom', 'kiri' => 'left', 'kanan' => 'right'] as $field => $key)
                <div>
                    <label for="margin_{{ $field }}" class="mb-1 block text-xs text-ink-muted">{{ ucfirst($field) }}</label>
                    <input type="number" step="0.1" min="0" max="10" id="margin_{{ $field }}" name="margin_{{ $field }}"
                        value="{{ old('margin_'.$field, $jenis_surat->{'margin_'.$field} ?? $defaults[$key]) }}" required class="{{ $input }}">
                    @error('margin_'.$field)<p class="mt-1 text-xs text-danger-700">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="space-y-3 border-t border-line pt-4">
        <p class="{{ $label }}">Halaman Lanjutan (Otomatis)</p>
        <div class="rounded-md border border-line bg-surface px-4 py-3 text-xs text-ink">
            <p><span class="font-medium">Kop surat</span> otomatis tampil di setiap halaman baru saat PDF diekspor.</p>
            <p class="mt-1"><span class="font-medium">Footer/tanda tangan</span> otomatis diambil dari tabel paling bawah. Nomor NIP dikosongkan otomatis.</p>
        </div>
        <input type="hidden" name="ulang_kop" value="1">
        <label class="flex min-h-11 cursor-pointer items-center gap-2 text-sm text-ink">
            <input type="checkbox" value="1" id="ulang_footer" name="ulang_footer" @checked(old('ulang_footer', $jenis_surat->ulang_footer ?? false)) class="size-5 rounded-sm border border-control accent-brand">
            Gunakan footer kustom (ganti footer otomatis)
        </label>
        <div>
            <label for="footer_surat" class="{{ $label }}">Isi Footer Kustom <span class="font-normal text-ink-muted">(opsional)</span></label>
            <textarea id="footer_surat" name="footer_surat" rows="2" placeholder="Kosongkan untuk memakai tabel tanda tangan paling bawah secara otomatis" class="{{ $input }}">{{ old('footer_surat', $jenis_surat->footer_surat ?? '') }}</textarea>
            <p class="mt-1 text-xs text-ink-muted">Jika diisi dan opsi di atas aktif, footer ini dipakai. Mendukung kode isian.</p>
            @error('footer_surat')<p class="mt-1 text-xs text-danger-700">{{ $message }}</p>@enderror
        </div>
    </div>
</section>
