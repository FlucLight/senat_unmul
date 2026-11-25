@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
    @php
        $input = 'h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
    @endphp

    <div class="space-y-6">
        <x-breadcrumb :items="[['label' => 'Beranda', 'url' => route('beranda')], ['label' => 'Kelola Akun']]" />

        <x-page-header title="Kelola Akun" description="Tambahkan pengurus, atur peran, dan atur ulang kata sandi." />

        @if (session('status'))
            <x-alert variant="success">{{ session('status') }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert variant="error">
                <ul class="list-inside list-disc space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        {{-- Tambah akun --}}
        <section class="rounded-md border border-line bg-surface p-6 sm:max-w-xl">
            <h2 class="font-serif text-xl font-semibold text-ink">Tambah Akun</h2>
            <div class="mt-1 border-b border-line pb-3"></div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="mt-4 space-y-4">
                @csrf

                <div>
                    <label for="nip" class="mb-1 block text-sm font-medium text-ink">NIP <span class="font-normal text-ink-muted">(wajib)</span></label>
                    <input id="nip" name="nip" value="{{ old('nip') }}" required inputmode="numeric" class="tnum {{ $input }}">
                </div>

                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-ink">Nama <span class="font-normal text-ink-muted">(wajib)</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="{{ $input }}">
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-ink">Surel <span class="font-normal text-ink-muted">(opsional)</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="{{ $input }}">
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-ink">Kata sandi sementara <span class="font-normal text-ink-muted">(wajib)</span></label>
                    <input id="password" name="password" type="password" required class="{{ $input }}">
                    <p class="mt-1 text-sm text-ink-muted">Sampaikan kata sandi ini kepada pengguna yang bersangkutan.</p>
                </div>

                <div>
                    <label for="role" class="mb-1 block text-sm font-medium text-ink">Peran</label>
                    <select id="role" name="role" class="{{ $input }}">
                        <option value="operator" @selected(old('role') === 'operator')>Operator</option>
                        @if (auth()->user()->role === 'admin')
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        @endif
                    </select>
                </div>

                <label class="flex min-h-11 cursor-pointer items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="is_active" value="1" checked class="size-5 rounded-sm border border-control accent-brand">
                    Aktif
                </label>

                <x-button type="submit">Tambah Akun</x-button>
            </form>
        </section>

        {{-- Daftar akun --}}
        <section class="overflow-hidden rounded-md border border-line bg-surface">
            <div class="border-b border-line px-6 py-4">
                <h2 class="font-serif text-xl font-semibold text-ink">Daftar Akun</h2>
                <p class="mt-1 text-sm text-ink-muted">{{ $users->count() }} pengguna terdaftar.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <caption class="sr-only">Daftar akun pengurus Senat FT</caption>
                    <thead class="border-b border-line-strong bg-paper-200 text-xs font-semibold text-ink-muted">
                        <tr>
                            <th scope="col" class="px-4 py-3">NIP</th>
                            <th scope="col" class="px-4 py-3">Nama</th>
                            <th scope="col" class="px-4 py-3">Peran</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($users as $u)
                            <tr class="transition-colors hover:bg-paper">
                                <td class="tnum px-4 py-3 text-ink">{{ $u->nip }}</td>
                                <td class="px-4 py-3 font-medium text-ink">{{ $u->name }}</td>
                                <td class="px-4 py-3 capitalize text-ink-muted">{{ $u->role }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :status="$u->is_active ? 'aktif' : 'nonaktif'" />
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.users.update', $u) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $u->name }}">
                                            <input type="hidden" name="email" value="{{ $u->email }}">
                                            <input type="hidden" name="role" value="{{ $u->role }}">
                                            <input type="hidden" name="is_active" value="{{ $u->is_active ? 0 : 1 }}">
                                            <button type="submit" class="inline-flex h-8 items-center rounded-md border border-control bg-surface px-3 text-xs font-medium text-ink transition-colors hover:bg-paper">
                                                {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.resetPassword', $u) }}" class="flex items-center gap-2">
                                            @csrf
                                            <label for="password-{{ $u->id }}" class="sr-only">Kata sandi baru untuk {{ $u->name }}</label>
                                            <input id="password-{{ $u->id }}" type="password" name="password" placeholder="Kata sandi baru" required
                                                class="h-8 w-36 rounded-md border border-control bg-surface px-2 text-xs text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
                                            <button type="submit" class="inline-flex h-8 items-center rounded-md border border-control bg-surface px-3 text-xs font-medium text-ink transition-colors hover:bg-paper">
                                                Atur Ulang
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
