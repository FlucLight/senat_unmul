@extends('layouts.app')

@section('title', 'Edit Surat')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('surat.index') }}" class="text-sm font-medium text-green-700 hover:underline">&larr; Kembali ke daftar surat</a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">Edit Surat</h2>
        </div>
        <div class="rounded-lg border border-frame bg-surface p-6">
            @include('surat._form', ['surat' => $surat])
        </div>
    </div>
@endsection
