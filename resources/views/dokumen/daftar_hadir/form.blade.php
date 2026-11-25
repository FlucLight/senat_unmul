@extends('layouts.app')

@section('title', 'Buat Daftar Hadir')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
            ['label' => 'Buat Daftar Hadir'],
        ]" />

        <x-page-header title="Buat Daftar Hadir" description="Isi data acara dan peserta, pilih varian template, lalu buat dokumen untuk disunting." />

        @if ($errors->any())
            <x-alert variant="error">
                <p class="font-semibold">Terdapat {{ $errors->count() }} isian yang perlu diperbaiki.</p>
                <ul class="mt-1 list-inside list-disc space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ route('dokumen.daftar-hadir.store') }}">
            @csrf
            <input type="hidden" name="form_mode" value="generator">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-xl font-semibold text-ink">Informasi Acara</h2>
                        <p class="mt-1 text-sm text-ink-muted">Data ini akan digunakan untuk menghasilkan dokumen daftar hadir.</p>
                        <div class="mt-6 space-y-4">
                            <div>
                                <label for="nama_acara" class="block text-xs font-medium text-ink-700">Nama acara / rapat <span class="text-error-text">*</span></label>
                                <input type="text" name="nama_acara" id="nama_acara" value="{{ old('nama_acara', $content['nama_acara'] ?? '') }}" required class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div><label for="tanggal" class="block text-xs font-medium text-ink-700">Tanggal acara</label><input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $content['tanggal'] ?? date('Y-m-d')) }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900"></div>
                                <div><label for="waktu_mulai" class="block text-xs font-medium text-ink-700">Waktu mulai</label><input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai', $content['waktu_mulai'] ?? '') }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900"></div>
                                <div><label for="waktu_selesai" class="block text-xs font-medium text-ink-700">Waktu selesai</label><input type="time" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai', $content['waktu_selesai'] ?? '') }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900"></div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div><label for="tempat" class="block text-xs font-medium text-ink-700">Tempat / lokasi</label><input type="text" name="tempat" id="tempat" value="{{ old('tempat', $content['tempat'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900"></div>
                                <div><label for="penyelenggara" class="block text-xs font-medium text-ink-700">Penyelenggara / pimpinan rapat</label><input type="text" name="penyelenggara" id="penyelenggara" value="{{ old('penyelenggara', $content['penyelenggara'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900"></div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-xl font-semibold text-ink">Varian Template</h2>
                        <div class="mt-4 space-y-2 text-sm text-ink">
                            @php $variant = old('variant', $content['variant'] ?? 'zigzag'); @endphp
                            <label class="flex items-start gap-2"><input type="radio" name="variant" value="zigzag" @checked($variant==='zigzag')> <span><strong>Standar (TTD Zigzag)</strong><br><span class="text-xs text-ink-muted">Tanda tangan ganjil kiri, genap kanan.</span></span></label>
                            <label class="flex items-start gap-2"><input type="radio" name="variant" value="satu_kolom" @checked($variant==='satu_kolom')> <span><strong>1 Kolom TTD</strong><br><span class="text-xs text-ink-muted">Satu kolom paraf/tanda tangan.</span></span></label>
                            <label class="flex items-start gap-2"><input type="radio" name="variant" value="blank" @checked($variant==='blank')> <span><strong>Blank Siap Cetak</strong><br><span class="text-xs text-ink-muted">Baris kosong, info acara garis isian.</span></span></label>
                        </div>
                        <div class="mt-3">
                            <label for="blank_rows" class="block text-xs font-medium text-ink-700">Jumlah baris kosong (khusus Blank)</label>
                            <input type="number" min="1" max="100" name="blank_rows" id="blank_rows" value="{{ old('blank_rows', $content['blank_rows'] ?? 20) }}" class="tnum mt-1.5 w-32 rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900">
                        </div>
                    </section>

                    <section class="rounded-md border border-line bg-surface p-6">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <h2 class="font-serif text-xl font-semibold text-ink">Daftar Peserta</h2>
                                <p class="mt-1 text-sm text-ink-muted">Peserta akan dimasukkan ke tabel dokumen. Anda masih bisa menyuntingnya di editor.</p>
                            </div>
                            <button type="button" id="btn-add-row" class="inline-flex items-center gap-1.5 rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 hover:bg-surface-muted">Tambah baris</button>
                        </div>
                        <div class="mt-4 overflow-hidden rounded-md border border-frame">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-surface-muted text-xs font-medium text-ink-700"><tr><th class="w-12 px-3 py-2.5 text-center">No.</th><th class="px-3 py-2.5">Nama peserta</th><th class="px-3 py-2.5">Jabatan / Instansi</th><th class="w-12 px-3 py-2.5 text-center">Hapus</th></tr></thead>
                                <tbody id="peserta-tbody" class="divide-y divide-frame">
                                    @php $rows = old('peserta', $content['peserta'] ?? []); if (empty($rows)) $rows = [['nama'=>'','jabatan_instansi'=>''],['nama'=>'','jabatan_instansi'=>'']]; @endphp
                                    @foreach ($rows as $index => $p)
                                    <tr class="peserta-row">
                                        <td class="tnum px-3 py-2 text-center text-xs text-ink-400"><span class="row-number">{{ $index + 1 }}</span></td>
                                        <td class="px-3 py-2"><input type="text" name="peserta[{{ $index }}][nama]" value="{{ $p['nama'] ?? '' }}" class="peserta-input-nama w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs"></td>
                                        <td class="px-3 py-2"><input type="text" name="peserta[{{ $index }}][jabatan_instansi]" value="{{ $p['jabatan_instansi'] ?? '' }}" class="w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs"></td>
                                        <td class="px-3 py-2 text-center"><button type="button" class="btn-remove-row text-ink-400 hover:text-error-text">Hapus</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
                <aside class="lg:col-span-4">
                    <div class="space-y-4 lg:sticky lg:top-24">
                        <section class="rounded-md border border-line bg-surface p-6">
                            <h2 class="font-serif text-lg font-semibold text-ink">Langkah Berikutnya</h2>
                            <p class="mt-2 text-sm text-ink-muted">Sistem akan membuat dokumen daftar hadir dari data ini dan membuka editor TinyMCE.</p>
                            <div class="mt-4"><x-button type="submit" class="w-full">Buat Dokumen</x-button></div>
                        </section>
                    </div>
                </aside>
            </div>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('peserta-tbody');
        function renumber(){ tbody.querySelectorAll('.peserta-row').forEach((row,i)=>{ row.querySelector('.row-number').textContent=i+1; const inputs=row.querySelectorAll('input'); if(inputs[0]) inputs[0].name=`peserta[${i}][nama]`; if(inputs[1]) inputs[1].name=`peserta[${i}][jabatan_instansi]`; }); }
        function addRow(nama='',jab=''){ const i=tbody.querySelectorAll('.peserta-row').length; const tr=document.createElement('tr'); tr.className='peserta-row'; tr.innerHTML=`<td class="tnum px-3 py-2 text-center text-xs text-ink-400"><span class="row-number">${i+1}</span></td><td class="px-3 py-2"><input type="text" name="peserta[${i}][nama]" value="${nama.replace(/"/g,'&quot;')}" class="peserta-input-nama w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs"></td><td class="px-3 py-2"><input type="text" name="peserta[${i}][jabatan_instansi]" value="${jab.replace(/"/g,'&quot;')}" class="w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs"></td><td class="px-3 py-2 text-center"><button type="button" class="btn-remove-row text-ink-400 hover:text-error-text">Hapus</button></td>`; tbody.appendChild(tr); }
        document.getElementById('btn-add-row')?.addEventListener('click', ()=>{ addRow(); renumber(); });
        tbody.addEventListener('click', (e)=>{ const btn=e.target.closest('.btn-remove-row'); if(!btn) return; btn.closest('.peserta-row')?.remove(); if(!tbody.querySelector('.peserta-row')) addRow(); renumber(); });
    });
    </script>
@endsection
