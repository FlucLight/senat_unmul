@php
    $prefix = $prefix ?? 'f';
    $showSearch = $showSearch ?? false;
    $withButtons = $withButtons ?? true;

    $inputClass = 'mt-1 w-full rounded-md border border-control bg-surface px-3 py-2 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
    $labelClass = 'block text-sm font-medium text-ink';
@endphp

@if ($showSearch)
    <div class="sm:col-span-2 lg:col-span-2">
        <label for="{{ $prefix }}-q" class="{{ $labelClass }}">Cari nomor atau judul</label>
        <input type="search" name="q" id="{{ $prefix }}-q" value="{{ $filters['q'] ?? '' }}"
            placeholder="Contoh: Undangan Rapat"
            class="{{ $inputClass }}">
    </div>
@endif

<div>
    <label for="{{ $prefix }}-jenis" class="{{ $labelClass }}">Jenis Dokumen</label>
    <select name="jenis" id="{{ $prefix }}-jenis" class="{{ $inputClass }}">
        <option value="">Semua</option>
        @foreach (\App\Models\Document::JENIS as $key => $label)
            <option value="{{ $key }}" @selected(($filters['jenis'] ?? '') === $key)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="{{ $prefix }}-status" class="{{ $labelClass }}">Status</label>
    <select name="status" id="{{ $prefix }}-status" class="{{ $inputClass }}">
        <option value="">Semua</option>
        <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draf</option>
        <option value="final" @selected(($filters['status'] ?? '') === 'final')>Final</option>
    </select>
</div>

<div>
    <label for="{{ $prefix }}-pembuat" class="{{ $labelClass }}">Pembuat</label>
    <select name="pembuat" id="{{ $prefix }}-pembuat" class="{{ $inputClass }}">
        <option value="">Semua pengurus</option>
        @foreach ($pembuatList as $user)
            <option value="{{ $user->id }}" @selected(($filters['pembuat'] ?? '') == $user->id)>{{ $user->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="{{ $prefix }}-tanggal_awal" class="{{ $labelClass }}">Dibuat dari</label>
    <input type="date" name="tanggal_awal" id="{{ $prefix }}-tanggal_awal" value="{{ $filters['tanggal_awal'] ?? '' }}"
        class="tnum {{ $inputClass }}">
</div>

<div>
    <label for="{{ $prefix }}-tanggal_akhir" class="{{ $labelClass }}">Hingga</label>
    <input type="date" name="tanggal_akhir" id="{{ $prefix }}-tanggal_akhir" value="{{ $filters['tanggal_akhir'] ?? '' }}"
        class="tnum {{ $inputClass }}">
</div>

@if ($withButtons)
    <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-6">
        <x-button type="submit">Terapkan</x-button>
        <x-button href="{{ route('dokumen.index') }}" variant="ghost">Atur Ulang</x-button>
    </div>
@endif
