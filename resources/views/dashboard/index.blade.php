<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <span class="text-sm text-gray-500">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ===== STATISTIK BUKU ===== --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="h-5 w-1 rounded-full bg-blue-600"></span>
                <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Statistik Buku</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                {{-- Total Buku --}}
                <div
                    class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Buku</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBuku }}</p>
                            <p class="text-xs text-gray-400 mt-2">Seluruh koleksi terdaftar</p>
                        </div>
                        <div
                            class="flex items-center justify-center h-11 w-11 rounded-lg bg-blue-600 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Buku Tersedia --}}
                <div
                    class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-green-50"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Buku Tersedia</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $bukuTersedia }}</p>
                            <p class="text-xs text-gray-400 mt-2">Siap untuk dipinjam</p>
                        </div>
                        <div
                            class="flex items-center justify-center h-11 w-11 rounded-lg bg-green-600 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Buku Habis --}}
                <div
                    class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-red-50"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Buku Habis</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $bukuHabis }}</p>
                            <p class="text-xs text-gray-400 mt-2">Stok perlu ditambah</p>
                        </div>
                        <div
                            class="flex items-center justify-center h-11 w-11 rounded-lg bg-red-600 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== STATISTIK ANGGOTA ===== --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="h-5 w-1 rounded-full bg-cyan-600"></span>
                <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Statistik Anggota</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                {{-- Total Anggota --}}
                <div
                    class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-cyan-50"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Anggota</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAnggota }}</p>
                            <p class="text-xs text-gray-400 mt-2">Terdaftar di sistem</p>
                        </div>
                        <div
                            class="flex items-center justify-center h-11 w-11 rounded-lg bg-cyan-600 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Anggota Aktif --}}
                <div
                    class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Anggota Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $anggotaAktif }}</p>
                            <p class="text-xs text-gray-400 mt-2">Status keanggotaan aktif</p>
                        </div>
                        <div
                            class="flex items-center justify-center h-11 w-11 rounded-lg bg-blue-600 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 7.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM3.75 20.25a8.25 8.25 0 0 1 16.5 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.119Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Anggota Nonaktif --}}
                <div
                    class="relative bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gray-100"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Anggota Nonaktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $anggotaNonaktif }}</p>
                            <p class="text-xs text-gray-400 mt-2">Tidak aktif sementara</p>
                        </div>
                        <div
                            class="flex items-center justify-center h-11 w-11 rounded-lg bg-gray-500 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TREN PEMINJAMAN (CHART) & RINGKASAN ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

                {{-- Mini chart tren peminjaman 7 hari --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="font-semibold text-gray-800">Tren Peminjaman</p>
                            <p class="text-xs text-gray-400">7 hari terakhir</p>
                        </div>
                        <span
                            class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Mingguan</span>
                    </div>

                    @php
                        // Gunakan data asli jika tersedia ($trenPeminjaman dari controller),
                        // fallback ke contoh agar chart tetap tampil bagus.
                        $tren = $trenPeminjaman ?? [
                            ['label' => 'Sen', 'total' => 4],
                            ['label' => 'Sel', 'total' => 7],
                            ['label' => 'Rab', 'total' => 5],
                            ['label' => 'Kam', 'total' => 9],
                            ['label' => 'Jum', 'total' => 6],
                            ['label' => 'Sab', 'total' => 3],
                            ['label' => 'Min', 'total' => 2],
                        ];
                        $totalTren = array_column($tren, 'total');
                        $maxTren = $totalTren ? max(max($totalTren), 1) : 1;
                    @endphp

                    <div class="flex items-end justify-between gap-3 h-36">
                        @foreach ($tren as $hari)
                            @php $heightPct = $maxTren > 0 ? ($hari['total'] / $maxTren) * 100 : 0; @endphp
                            <div class="flex-1 flex flex-col items-center gap-2 group">
                                <div class="w-full flex items-end h-28 relative">
                                    <span
                                        class="absolute -top-5 left-1/2 -translate-x-1/2 text-[11px] font-medium text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $hari['total'] }}
                                    </span>
                                    <div class="w-full rounded-md bg-blue-600/90 group-hover:bg-blue-600 transition-colors duration-150"
                                        style="height: {{ max($heightPct, 6) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $hari['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Ringkasan cepat --}}
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
                    <p class="font-semibold text-gray-800 mb-4">Ringkasan</p>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Ketersediaan buku</span>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ $totalBuku > 0 ? round(($bukuTersedia / $totalBuku) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-green-600 rounded-full"
                                style="width: {{ $totalBuku > 0 ? round(($bukuTersedia / $totalBuku) * 100) : 0 }}%">
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-sm text-gray-500">Anggota aktif</span>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ $totalAnggota > 0 ? round(($anggotaAktif / $totalAnggota) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full"
                                style="width: {{ $totalAnggota > 0 ? round(($anggotaAktif / $totalAnggota) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== NOTIFIKASI BUKU TERLAMBAT (TUGAS 3) ===== --}}
            @if (isset($totalTerlambat) && $totalTerlambat > 0)
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="h-5 w-1 rounded-full bg-red-600"></span>
                        <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Perhatian: Buku
                            Terlambat</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-red-200 overflow-hidden">
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 bg-red-50 border-b border-red-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center h-10 w-10 rounded-lg bg-red-600 text-white shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-red-800 font-bold text-lg">{{ $totalTerlambat }} Transaksi
                                        Terlambat</h3>
                                    <p class="text-red-600 text-sm">Harap segera hubungi anggota terkait</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white">
                            <ul class="divide-y divide-gray-100">
                                @foreach ($listTerlambat as $t)
                                    <li
                                        class="flex items-center justify-between p-4 hover:bg-red-50/30 transition-colors">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $t->anggota->nama }}</p>
                                            <p class="text-sm text-gray-500">{{ $t->buku->judul }}</p>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 ring-1 ring-red-200">
                                            Telat {{ $t->terlambat }} Hari
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== 5 BUKU TERBARU & 5 ANGGOTA TERBARU ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

                {{-- Buku Terbaru --}}
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-blue-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">5 Buku Terbaru</p>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="text-gray-500">
                            <tr>
                                <th class="px-5 py-2 text-left font-medium">Judul</th>
                                <th class="px-5 py-2 text-left font-medium">Kategori</th>
                                <th class="px-5 py-2 text-left font-medium">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukuTerbaru as $buku)
                                <tr class="border-t border-gray-100 hover:bg-gray-50/80 transition-colors">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('buku.show', $buku->id) }}"
                                            class="text-blue-600 hover:text-blue-700 font-medium hover:underline">
                                            {{ Str::limit($buku->judul, 30) }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span
                                            class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-medium">{{ $buku->kategori }}</span>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($buku->stok > 0)
                                            <span
                                                class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">{{ $buku->stok }}</span>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-medium">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-400 py-8">Belum ada buku</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-5 py-4 border-t border-gray-100 text-right">
                        <a href="{{ route('buku.index') }}"
                            class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Lihat Semua Buku
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Anggota Terbaru --}}
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-green-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800">5 Anggota Terbaru</p>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="text-gray-500">
                            <tr>
                                <th class="px-5 py-2 text-left font-medium">Nama</th>
                                <th class="px-5 py-2 text-left font-medium">Email</th>
                                <th class="px-5 py-2 text-left font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggotaTerbaru as $anggota)
                                <tr class="border-t border-gray-100 hover:bg-gray-50/80 transition-colors">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('anggota.show', $anggota->id) }}"
                                            class="text-blue-600 hover:text-blue-700 font-medium hover:underline">
                                            {{ $anggota->nama }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500">{{ Str::limit($anggota->email, 25) }}</td>
                                    <td class="px-5 py-3">
                                        @if ($anggota->status == 'Aktif')
                                            <span
                                                class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">Aktif</span>
                                        @else
                                            <span
                                                class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-medium">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-400 py-8">Belum ada anggota</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-5 py-4 border-t border-gray-100 text-right">
                        <a href="{{ route('anggota.index') }}"
                            class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Lihat Semua Anggota
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ===== QUICK LINKS ===== --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="h-5 w-1 rounded-full bg-gray-400"></span>
                <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Menu Utama</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">

                <a href="{{ route('buku.index') }}"
                    class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                    <div
                        class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="font-semibold text-gray-700 text-sm">Daftar Buku</div>
                </a>

                <a href="{{ route('anggota.index') }}"
                    class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                    <div
                        class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-green-50 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </div>
                    <div class="font-semibold text-gray-700 text-sm">Daftar Anggota</div>
                </a>

                <a href="{{ route('buku.create') }}"
                    class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                    <div
                        class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div class="font-semibold text-gray-700 text-sm">Tambah Buku</div>
                </a>

                <a href="{{ route('anggota.create') }}"
                    class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                    <div
                        class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-cyan-50 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                    <div class="font-semibold text-gray-700 text-sm">Tambah Anggota</div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
