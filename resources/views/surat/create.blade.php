@extends('layouts.app')

@section('title', 'Buat Surat')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('surat.index') }}" class="text-sm font-medium text-green-700 hover:underline">&larr; Kembali ke daftar surat</a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">Buat Surat Baru</h2>
        </div>

        <div class="rounded-lg border border-frame bg-surface p-6">
            @if ($jenisSurat)
                <p class="mb-5 text-sm text-ink-400">Isi detail surat berdasarkan template <span class="font-medium text-ink-700">{{ $jenisSurat->nama_surat }}</span>.</p>
                @include('surat._form', ['surat' => $surat ?? null])
            @else
                <p class="mb-5 text-sm text-ink-400">Pilih template surat yang akan digunakan. Kelola template di <a href="{{ route('jenis_surat.index') }}" class="text-green-700 hover:underline">Template Surat</a>.</p>

                @if ($jenisSuratList->isEmpty())
                    <div class="rounded-md border border-gold-100 bg-gold-50 px-4 py-3 text-sm text-ink-700">
                        Belum ada template surat. <a href="{{ route('jenis_surat.create') }}" class="font-medium text-green-700 hover:underline">Tambah template surat terlebih dahulu</a>.
                    </div>
                @else
                    <form method="GET" action="{{ route('surat.create') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <div class="flex-1">
                            <label for="jenis_surat_id" class="mb-1 block text-sm font-medium text-ink-700">Template Surat</label>
                            <select id="jenis_surat_id" name="jenis_surat_id" required class="w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                                <option value="">-- Pilih Template Surat --</option>
                                @foreach ($jenisSuratList as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="rounded-md bg-gold-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gold-500">Lanjut Buat Surat</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
@endsection
