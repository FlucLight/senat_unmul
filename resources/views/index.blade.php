@extends('layouts.app')

@section('title', 'Pembuat Surat')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="font-display text-[28px] font-semibold leading-tight text-ink-900">Pembuat Surat</h2>
            <p class="mt-1 text-sm text-ink-400">Buat dan kelola surat resmi Senat FT dari template.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-frame bg-surface p-6">
                <h3 class="font-display text-lg font-semibold text-ink-900">Surat</h3>
                <p class="mt-2 text-sm text-ink-500">Buat surat baru dari template, kelola, lalu pratinjau atau unduh sebagai PDF.</p>
                <a href="{{ route('surat.index') }}" class="mt-4 inline-flex rounded-md bg-gold-600 px-4 py-2 text-sm font-semibold text-white hover:bg-gold-500">Kelola &amp; Buat Surat</a>
            </div>
            <div class="rounded-lg border border-frame bg-surface p-6">
                <h3 class="font-display text-lg font-semibold text-ink-900">Template Surat</h3>
                <p class="mt-2 text-sm text-ink-500">Atur template/format surat, kop, logo, ukuran kertas, dan margin.</p>
                <a href="{{ route('jenis_surat.index') }}" class="mt-4 inline-flex rounded-md border border-frame-strong bg-surface px-4 py-2 text-sm font-medium text-ink-900 hover:bg-surface-muted">Kelola Template Surat</a>
            </div>
        </div>
    </div>
@endsection
