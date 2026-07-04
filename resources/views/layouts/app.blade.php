<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--
        ANTI-FLICKER SCRIPT (harus di <head>, sebelum CSS/body dirender).
        Tujuannya: begitu HTML mulai di-parse, class "dark" pada <html>
        SUDAH ditentukan dari localStorage, sebelum browser sempat
        menggambar body dengan warna terang lalu "berkedip" ke gelap.
        Tanpa ini, akan selalu ada flash putih sekilas saat reload
        walaupun tema tersimpan adalah dark.
    --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    {{--
        transition-colors + duration diberi ke wrapper utama supaya
        perpindahan bg/warna teks terasa halus, bukan berubah mendadak.
    --}}
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 space-y-4">

                    {{-- Judul Halaman & Tombol Aksi (Bagian Atas) --}}
                    <div class="w-full overflow-x-auto">
                        {{ $header }}
                    </div>

                    {{-- Form Pencarian Tailwind (Bagian Bawah, Ujung ke Ujung) --}}
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <label for="default-search"
                            class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-100 sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                {{-- Icon Search SVG --}}
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>

                            {{-- Input dengan padding kanan lebih besar (pr-24) agar teks tidak tertutup tombol --}}
                            <input type="search" name="q" id="default-search" value="{{ request('q') }}"
                                class="block w-full p-2.5 pl-10 pr-24 text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300"
                                placeholder="Cari buku, anggota, transaksi..." required>

                            {{-- Grup Tombol Kanan --}}
                            <div class="absolute right-1.5 bottom-1.5 flex items-center gap-1">
                                {{-- Tombol X (Keluar/Reset) hanya muncul jika sedang mencari --}}
                                @if (request('q'))
                                    <a href="{{ route('dashboard') }}"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition"
                                        title="Tutup Pencarian">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                @endif

                                <button type="submit"
                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-md text-xs px-3 py-1.5 transition">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>

</html>
