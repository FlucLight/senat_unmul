@extends('layouts.app')

@section('title', 'Daftar Isi')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Daftar Isi Dokumen</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Cari dan akses seluruh dokumen Senat FT. {{ $documents->total() }} dokumen.
                </p>
            </div>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('dokumen.index') }}" class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-900/5 sm:p-5">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
                <div class="lg:col-span-2">
                    <label for="q" class="block text-xs font-medium text-slate-600">Cari nomor surat / judul</label>
                    <input type="search" name="q" id="q" value="{{ $filters['q'] ?? '' }}"
                        placeholder="Contoh: Undangan Rapat"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>

                <div>
                    <label for="jenis" class="block text-xs font-medium text-slate-600">Jenis dokumen</label>
                    <select name="jenis" id="jenis"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua</option>
                        @foreach (\App\Models\Document::JENIS as $key => $label)
                            <option value="{{ $key }}" @selected(($filters['jenis'] ?? '') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-medium text-slate-600">Status</label>
                    <select name="status" id="status"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua</option>
                        <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
                        <option value="final" @selected(($filters['status'] ?? '') === 'final')>Final</option>
                    </select>
                </div>

                <div>
                    <label for="pembuat" class="block text-xs font-medium text-slate-600">Pembuat</label>
                    <select name="pembuat" id="pembuat"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Semua pengurus</option>
                        @foreach ($pembuatList as $user)
                            <option value="{{ $user->id }}" @selected(($filters['pembuat'] ?? '') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal_awal" class="block text-xs font-medium text-slate-600">Tanggal dibuat</label>
                    <div class="mt-1 flex items-center gap-2">
                        <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $filters['tanggal_awal'] ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <span class="text-slate-400">s/d</span>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $filters['tanggal_akhir'] ?? '' }}"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    Terapkan
                </button>
                <a href="{{ route('dokumen.index') }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                    Reset
                </a>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <th class="px-4 py-3">Nomor Surat</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Judul / Perihal</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Dibuat oleh</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($documents as $document)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-900">
                                    {{ $document->nomor_surat ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        {{ $document->jenis_label }}
                                    </span>
                                </td>
                                <td class="max-w-[16rem] px-4 py-3">
                                    <a href="{{ route('dokumen.show', $document) }}" class="font-medium text-slate-900 transition hover:text-blue-700 hover:underline">
                                        {{ $document->judul }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-500">
                                    {{ $document->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @if ($document->isFinal())
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            Final
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-500">
                                    {{ $document->creator->name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('dokumen.show', $document) }}" title="Lihat detail"
                                            class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-blue-700">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>

                                        @if ($document->isDraft())
                                            <a href="{{ route('dokumen.edit', [$document->jenis, $document]) }}" title="Edit"
                                                class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-blue-700">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('dokumen.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen draft ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus"
                                                    class="rounded-lg p-2 text-slate-500 transition hover:bg-red-50 hover:text-red-600">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('dokumen.pdf', $document) }}" title="Download PDF"
                                                class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-emerald-700">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                            </a>
                                            @if ($document->qr_verification_url)
                                                <button type="button" title="Salin link verifikasi QR"
                                                    data-copy-url="{{ $document->qr_verification_url }}"
                                                    class="copy-qr-link rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-blue-700">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                            </svg>
                                        </div>
                                        <p class="mt-4 text-sm font-semibold text-slate-700">
                                            {{ $filtered ? 'Tidak ada dokumen yang cocok dengan filter.' : 'Belum ada dokumen.' }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            @if ($filtered)
                                                Coba ubah kata kunci atau filter pencarian.
                                            @else
                                                Dokumen yang dibuat pengurus akan tampil di sini.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($documents->hasPages())
                <div class="border-t border-slate-100 px-4 py-4">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection