@php
    $isEdit = isset($document) && $document;
    $actionUrl = $isEdit ? route('dokumen.' . str_replace('_', '-', $jenis) . '.update', $document) : route('dokumen.' . str_replace('_', '-', $jenis) . '.store');
    $perihal = old('perihal', $meta['perihal'] ?? $document?->judul ?? '');
    $tanggalSurat = old('tanggal_surat', $meta['tanggal_surat'] ?? now()->toDateString());
    $templateKey = old('template_key', $selectedTemplate ?? '');
    $body = old('body_html', $bodyHtml ?? '');
@endphp

<form method="POST" action="{{ $actionUrl }}" class="space-y-6" data-html-doc-form>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif
    <input type="hidden" name="form_mode" value="html">

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-8">
            <section class="rounded-md border border-line bg-surface p-6">
                <h2 class="font-serif text-xl font-semibold text-ink">Data Surat</h2>
                <div class="mt-1 border-b border-line pb-3"></div>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="perihal" class="block text-sm font-medium text-ink">Perihal / Judul <span class="font-normal text-ink-muted">(wajib)</span></label>
                        <input id="perihal" name="perihal" type="text" value="{{ $perihal }}" required
                            class="mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
                        @error('perihal')<p class="mt-1 text-xs text-danger-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tanggal_surat" class="block text-sm font-medium text-ink">Tanggal Surat <span class="font-normal text-ink-muted">(wajib)</span></label>
                        <input id="tanggal_surat" name="tanggal_surat" type="date" value="{{ $tanggalSurat }}" required
                            class="tnum mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
                        @error('tanggal_surat')<p class="mt-1 text-xs text-danger-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="template_key" class="block text-sm font-medium text-ink">Template</label>
                        <select id="template_key" name="template_key" class="mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
                            @foreach($templates as $key => $tpl)
                                <option value="{{ $key }}" data-body="{{ e($templateBodies[$key] ?? '') }}" @selected($templateKey === $key)>{{ $tpl['label'] }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-ink-muted">Mengganti template akan mengganti isi editor.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-md border border-line bg-surface p-6">
                <h2 class="font-serif text-xl font-semibold text-ink">Isi Dokumen</h2>
                <p class="mt-1 text-sm text-ink-muted">Kop surat ditampilkan otomatis. Token bertanda abu-abu tidak dapat diedit langsung.</p>

                <div class="mt-4 rounded-md border border-line bg-paper-200 p-4">
                    <div class="flex items-center gap-4 border-b border-line pb-3">
                        <img src="{{ asset('image.png') }}" alt="" class="h-12 w-auto object-contain">
                        <div class="min-w-0 text-center flex-1">
                            <p class="text-[11px] font-bold uppercase text-ink">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                            <p class="text-[13px] font-bold uppercase text-ink">Universitas Mulawarman</p>
                            <p class="text-[14px] font-bold uppercase text-ink">Fakultas Teknik — Senat Fakultas</p>
                            <p class="text-[9px] text-ink-muted">Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119</p>
                        </div>
                        <div class="w-12"></div>
                    </div>
                    <div class="h-0.5 bg-gold-600"></div>
                    <p class="mt-2 text-center text-[11px] text-ink-muted">Kop surat otomatis (tidak dapat diedit)</p>
                </div>

                <div class="mt-4">
                    <x-rich-editor name="body_html" id="body_html" :label="null" :value="$body" :required="true" />
                </div>
            </section>
        </div>

        <aside class="lg:col-span-4">
            <div class="space-y-4 lg:sticky lg:top-24">
                <section class="rounded-md border border-line bg-surface p-6">
                    <h2 class="font-serif text-lg font-semibold text-ink">Status Dokumen</h2>
                    <div class="mt-3 flex items-center gap-2">
                        <x-status-badge :status="$document?->status ?? 'draft'" />
                        <span class="text-sm text-ink-muted">{{ $document ? 'Dokumen tersimpan' : 'Dokumen baru' }}</span>
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-ink-muted">Nomor surat dan QR diberikan otomatis saat finalisasi.</p>
                    @if($document?->updated_at)
                        <p class="tnum mt-2 text-xs text-ink-muted">Terakhir disimpan {{ $document->updated_at->translatedFormat('d M Y, H.i') }} WITA</p>
                    @endif
                </section>

                <section class="rounded-md border border-line bg-surface p-6">
                    <div class="space-y-2">
                        <x-button type="submit" name="action" value="draft" variant="secondary" class="w-full">Simpan Draf</x-button>
                        <x-button type="submit" name="action" value="preview" variant="secondary" class="w-full">Pratinjau</x-button>
                        <x-button type="submit" name="action" value="final" class="w-full"
                            onclick="return confirm('Finalisasi dokumen? Setelah final, dokumen mendapat nomor resmi dan tidak dapat diubah atau dihapus.')">
                            Finalisasi
                        </x-button>
                    </div>
                    <p class="mt-3 text-xs leading-relaxed text-ink-muted">Finalisasi menerbitkan nomor resmi. Dokumen final tidak dapat diubah atau dihapus.</p>
                </section>
            </div>
        </aside>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('template_key');
    if (!select) return;
    let lastValue = select.value;
    select.addEventListener('change', function () {
        const opt = select.options[select.selectedIndex];
        const body = opt?.dataset?.body || '';
        const ed = window.tinymce?.get('body_html');
        const current = ed ? ed.getContent() : (document.getElementById('body_html')?.value || '');
        if (current.trim() !== '' && !confirm('Ganti template akan mengganti isi editor. Lanjutkan?')) {
            select.value = lastValue;
            return;
        }
        if (ed) ed.setContent(body); else document.getElementById('body_html').value = body;
        lastValue = select.value;
    });
});
</script>
