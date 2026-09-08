@php
    $prefix = $prefix ?? 'f';
    $showSearch = $showSearch ?? false;
    $withButtons = $withButtons ?? true;
@endphp

@if ($showSearch)
    <div class="sm:col-span-2 lg:col-span-2">
        <label for="{{ $prefix }}-q" class="block text-xs font-medium text-ink-700">Cari nomor surat / judul</label>
        <input type="search" name="q" id="{{ $prefix }}-q" value="{{ $filters['q'] ?? '' }}"
            placeholder="Contoh: Undangan Rapat"
            class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
    </div>
@endif

<div>
    <label for="{{ $prefix }}-jenis" class="block text-xs font-medium text-ink-700">Jenis dokumen</label>
    <select name="jenis" id="{{ $prefix }}-jenis"
        class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
        <option value="">Semua</option>
        @foreach (\App\Models\Document::JENIS as $key => $label)
            <option value="{{ $key }}" @selected(($filters['jenis'] ?? '') === $key)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="{{ $prefix }}-status" class="block text-xs font-medium text-ink-700">Status</label>
    <select name="status" id="{{ $prefix }}-status"
        class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
        <option value="">Semua</option>
        <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
        <option value="final" @selected(($filters['status'] ?? '') === 'final')>Final</option>
    </select>
</div>

<div>
    <label for="{{ $prefix }}-pembuat" class="block text-xs font-medium text-ink-700">Pembuat</label>
    <select name="pembuat" id="{{ $prefix }}-pembuat"
        class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
        <option value="">Semua pengurus</option>
        @foreach ($pembuatList as $user)
            <option value="{{ $user->id }}" @selected(($filters['pembuat'] ?? '') == $user->id)>{{ $user->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="{{ $prefix }}-tanggal_awal" class="block text-xs font-medium text-ink-700">Dibuat dari</label>
    <input type="date" name="tanggal_awal" id="{{ $prefix }}-tanggal_awal" value="{{ $filters['tanggal_awal'] ?? '' }}"
        class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
</div>

<div>
    <label for="{{ $prefix }}-tanggal_akhir" class="block text-xs font-medium text-ink-700">Hingga</label>
    <input type="date" name="tanggal_akhir" id="{{ $prefix }}-tanggal_akhir" value="{{ $filters['tanggal_akhir'] ?? '' }}"
        class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
</div>

@if ($withButtons)
    <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-6">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            Terapkan
        </button>
        <a href="{{ route('dokumen.index') }}" class="rounded-md px-4 py-2.5 text-sm font-medium text-ink-500 transition hover:text-ink-900">
            Reset
        </a>
    </div>
@endif