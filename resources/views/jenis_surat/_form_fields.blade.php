@php
    $defaults = config('senatitor.margins');
    $input = 'w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-100';
    $label = 'mb-1 block text-sm font-medium text-ink-700';
@endphp

<div class="rounded-lg border border-frame bg-surface-muted p-5 space-y-5">
    <p class="font-display text-base font-semibold text-ink-900">Pengaturan Kertas &amp; Logo</p>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="ukuran_kertas" class="{{ $label }}">Ukuran Kertas</label>
            <select id="ukuran_kertas" name="ukuran_kertas" required class="{{ $input }}">
                @foreach (config('senatitor.paper_sizes') as $value => $lbl)
                    <option value="{{ $value }}" @selected(old('ukuran_kertas', $jenis_surat->ukuran_kertas ?? 'a4') === $value)>{{ $lbl }}</option>
                @endforeach
            </select>
            @error('ukuran_kertas')<p class="mt-1 text-xs text-error-text">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="logo" class="{{ $label }}">Logo Kop Surat (opsional)</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="{{ $input }}">
            <p class="mt-1 text-xs text-ink-400">Kosongkan untuk logo default. JPG/PNG, maks. 2 MB.</p>
            @error('logo')<p class="mt-1 text-xs text-error-text">{{ $message }}</p>@enderror
            @if (!empty($jenis_surat?->logo))
                <div class="mt-2 flex items-center gap-3">
                    <img src="{{ asset($jenis_surat->logo) }}" alt="Logo saat ini" class="h-12 w-auto rounded border border-frame bg-white p-1">
                    <label class="flex items-center gap-2 text-xs text-ink-700">
                        <input type="checkbox" value="1" id="hapus_logo" name="hapus_logo" class="rounded border-frame text-green-700">
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
                    <label for="margin_{{ $field }}" class="mb-1 block text-xs text-ink-500">{{ ucfirst($field) }}</label>
                    <input type="number" step="0.1" min="0" max="10" id="margin_{{ $field }}" name="margin_{{ $field }}"
                        value="{{ old('margin_'.$field, $jenis_surat->{'margin_'.$field} ?? $defaults[$key]) }}" required class="{{ $input }}">
                    @error('margin_'.$field)<p class="mt-1 text-xs text-error-text">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="border-t border-frame pt-4 space-y-3">
        <p class="{{ $label }}">Halaman Lanjutan (Otomatis)</p>
        <div class="rounded-md border border-green-100 bg-green-50 px-4 py-3 text-xs text-ink-700">
            <p><span class="font-medium">Kop surat</span> otomatis tampil di setiap halaman baru saat PDF diekspor.</p>
            <p class="mt-1"><span class="font-medium">Footer/tanda tangan</span> otomatis diambil dari tabel paling bawah. Nomor NIP dikosongkan otomatis.</p>
        </div>
        <input type="hidden" name="ulang_kop" value="1">
        <label class="flex items-center gap-2 text-sm text-ink-700">
            <input type="checkbox" value="1" id="ulang_footer" name="ulang_footer" @checked(old('ulang_footer', $jenis_surat->ulang_footer ?? false)) class="rounded border-frame text-green-700">
            Gunakan footer kustom (ganti footer otomatis)
        </label>
        <div>
            <label for="footer_surat" class="{{ $label }}">Isi Footer Kustom (opsional)</label>
            <textarea id="footer_surat" name="footer_surat" rows="2" placeholder="Kosongkan untuk memakai tabel tanda tangan paling bawah secara otomatis" class="{{ $input }}">{{ old('footer_surat', $jenis_surat->footer_surat ?? '') }}</textarea>
            <p class="mt-1 text-xs text-ink-400">Jika diisi dan opsi di atas aktif, footer ini dipakai. Mendukung kode isian.</p>
            @error('footer_surat')<p class="mt-1 text-xs text-error-text">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
