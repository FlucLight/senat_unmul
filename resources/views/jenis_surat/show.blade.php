@extends('layouts.app')

@section('title', 'Pratinjau Template')

@push('styles')
    <style>
        .pratinjau-surat {
            background: #fff;
            box-shadow: 0 1px 4px rgba(34,34,29,.12);
            margin: 0 auto;
            max-width: 820px;
            min-height: 1160px;
            padding: 4rem;
            color: #222;
        }
        .pratinjau-surat .new-break {
            border-top: 2px dashed #c7c2b5;
            margin: 3rem -4rem;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('jenis_surat.index') }}" class="text-sm font-medium text-green-700 hover:underline">&larr; Kembali</a>
                <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">Pratinjau Template: {{ $jenis_surat->nama_surat }}</h2>
            </div>
            <a href="{{ route('jenis_surat.edit', $jenis_surat) }}" class="rounded-md border border-frame-strong bg-surface px-4 py-2 text-sm font-medium text-ink-900 hover:bg-surface-muted">Edit</a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-frame bg-surface-muted p-4 sm:p-8">
            <div class="pratinjau-surat">
                {!! $konten !!}
            </div>
        </div>
    </div>
@endsection
