<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda — Sistem Senat Fakultas Teknik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased">
    <main class="flex min-h-screen flex-col items-center justify-center px-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-xl ring-1 ring-slate-900/5">
            <p class="text-4xl">👋</p>
            <h1 class="mt-3 text-xl font-bold text-slate-900">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="mt-2 text-sm text-slate-500">
                Login berhasil. Halaman Beranda akan dibangun pada modul berikutnya.
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Keluar
                </button>
            </form>
        </div>
    </main>
</body>
</html>