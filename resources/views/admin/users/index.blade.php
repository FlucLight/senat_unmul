@extends('layouts.app')

@section('title', 'Kelola Akun Pengurus')

@section('content')
    <div class="w-full space-y-6">
        <h2 class="font-display text-[28px] font-semibold leading-tight text-ink-900">Kelola Akun Pengurus</h2>

        @if (session('status'))
            <div class="rounded-md border border-green-100 bg-green-100 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        {{-- Form tambah akun --}}
        <div class="rounded-lg border border-frame bg-surface p-5">
            <h3 class="font-display text-lg font-semibold text-ink-900">Tambah Akun Baru</h3>
            <form method="POST" action="{{ route('admin.users.store') }}" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                @csrf
                <input name="nip" placeholder="NIP" required class="rounded-md border border-frame bg-surface px-3 py-2 text-sm">
                <input name="name" placeholder="Nama" required class="rounded-md border border-frame bg-surface px-3 py-2 text-sm">
                <input name="email" placeholder="Email (opsional)" type="email" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm">
                <input name="password" placeholder="Password" type="password" required class="rounded-md border border-frame bg-surface px-3 py-2 text-sm">
                <select name="role" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm">
                    <option value="operator">operator</option>
                    <option value="admin">admin</option>
                </select>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" checked> Aktif
                </label>
                <button type="submit" class="rounded-md bg-gold-600 px-4 py-2 text-sm font-semibold text-white sm:col-span-3">Tambah Akun</button>
            </form>
        </div>

        {{-- Daftar akun --}}
        <div class="overflow-hidden rounded-lg border border-frame bg-surface">
            <table class="w-full text-sm">
                <thead class="bg-surface-muted text-xs font-medium text-ink-700">
                    <tr>
                        <th class="px-3 py-2 text-left">NIP</th>
                        <th class="px-3 py-2 text-left">Nama</th>
                        <th class="px-3 py-2 text-left">Role</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-frame">
                    @foreach ($users as $u)
                        <tr>
                            <td class="px-3 py-2 tnum">{{ $u->nip }}</td>
                            <td class="px-3 py-2">{{ $u->name }}</td>
                            <td class="px-3 py-2">{{ $u->role }}</td>
                            <td class="px-3 py-2">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="px-3 py-2">
                                <form method="POST" action="{{ route('admin.users.update', $u) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $u->name }}">
                                    <input type="hidden" name="email" value="{{ $u->email }}">
                                    <input type="hidden" name="role" value="{{ $u->role }}">
                                    <input type="hidden" name="is_active" value="{{ $u->is_active ? 0 : 1 }}">
                                    <button type="submit" class="rounded border border-frame bg-surface px-2 py-1 text-xs hover:bg-surface-muted">
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.resetPassword', $u) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="password" name="password" placeholder="Pass baru" required class="w-24 rounded border border-frame bg-surface px-2 py-1 text-xs">
                                    <button type="submit" class="rounded border border-frame bg-surface px-2 py-1 text-xs hover:bg-surface-muted">Reset</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection