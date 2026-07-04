<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dashboard Perpustakaan') }}
            </h2>

            <div class="flex items-center gap-3">
                {{-- Tombol Export PDF menggunakan Backend (DomPDF) --}}
                <a id="btn-export-pdf" href="{{ route('dashboard.export', ['period' => $period]) }}"
                    class="flex items-center gap-2 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg shadow-sm border border-red-100 dark:border-red-800 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span class="text-xs font-bold">Export PDF</span>
                </a>

                {{-- Tanggal --}}
                <div
                    class="flex items-center gap-2 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                    <span class="flex h-2 w-2 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span
                        class="text-xs font-semibold text-gray-600 dark:text-gray-300">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ========================================== --}}
            {{-- 0. FILTER RENTANG WAKTU GLOBAL (BARU)        --}}
            {{-- Mengubah filter ini akan meng-update:        --}}
            {{-- statistik "Transaksi/Denda Periode Ini" dan  --}}
            {{-- ke-4 chart di bawah, lewat AJAX (tanpa reload) --}}
            {{-- ========================================== --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-4 flex flex-wrap items-center gap-2 transition-colors duration-300">
                <span
                    class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mr-2">Periode:</span>

                <div id="period-filter" class="flex flex-wrap gap-2" data-current="{{ $period }}">
                    <button type="button" data-period="7hari"
                        class="period-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors">7
                        Hari</button>
                    <button type="button" data-period="30hari"
                        class="period-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors">30
                        Hari</button>
                    <button type="button" data-period="3bulan"
                        class="period-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors">3
                        Bulan</button>
                    <button type="button" data-period="6bulan"
                        class="period-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors">6
                        Bulan</button>
                    <button type="button" data-period="1tahun"
                        class="period-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors">1
                        Tahun</button>
                    <button type="button" data-period="semua"
                        class="period-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors">Semua</button>
                </div>

                {{-- Spinner kecil, muncul selama request AJAX berjalan --}}
                <span id="period-loading"
                    class="hidden items-center gap-2 text-xs text-indigo-600 dark:text-indigo-400 font-semibold ml-2">
                    <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    Memuat data...
                </span>
            </div>

            {{-- ========================================== --}}
            {{-- 1. RINGKASAN STATISTIK UTAMA --}}
            {{-- ========================================== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="h-5 w-1 rounded-full bg-indigo-600"></span>
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Ringkasan
                        Statistik</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                    <!-- Total Buku (snapshot, tidak terikat filter periode) -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-6 relative overflow-hidden hover:shadow-md transition-all duration-200">
                        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-50 dark:bg-blue-900/20">
                        </div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    Total Buku</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                                    {{ $stats['total_buku'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tersedia: <span
                                        class="font-semibold text-blue-600 dark:text-blue-400">{{ $bukuTersedia }}</span>
                                    | Habis: <span
                                        class="font-semibold text-red-500 dark:text-red-400">{{ $bukuHabis }}</span>
                                </p>
                            </div>
                            <div
                                class="p-3 bg-blue-500 rounded-lg text-white shadow-sm shadow-blue-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Anggota Aktif (snapshot) -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-6 relative overflow-hidden hover:shadow-md transition-all duration-200">
                        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-green-50 dark:bg-green-900/20">
                        </div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    Anggota Aktif</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                                    {{ $stats['total_anggota'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total: <span
                                        class="font-semibold text-green-600 dark:text-green-400">{{ $totalAnggota }}</span>
                                    | Nonaktif:
                                    <span
                                        class="font-semibold text-gray-400 dark:text-gray-500">{{ $anggotaNonaktif }}</span>
                                </p>
                            </div>
                            <div
                                class="p-3 bg-green-500 rounded-lg text-white shadow-sm shadow-green-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Sedang Dipinjam (snapshot) -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-6 relative overflow-hidden hover:shadow-md transition-all duration-200">
                        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-cyan-50 dark:bg-cyan-900/20">
                        </div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    Sedang Dipinjam
                                </p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                                    {{ $stats['sedang_dipinjam'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hari ini: <span
                                        class="font-semibold text-cyan-600 dark:text-cyan-400">{{ $stats['transaksi_hari_ini'] }}
                                        pinjam</span></p>
                            </div>
                            <div
                                class="p-3 bg-cyan-500 rounded-lg text-white shadow-sm shadow-cyan-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Terlambat Pengembalian (snapshot) -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-6 relative overflow-hidden hover:shadow-md transition-all duration-200">
                        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-red-50 dark:bg-red-900/20">
                        </div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    Terlambat</p>
                                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                                    {{ $stats['terlambat'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Butuh tindak lanjut</p>
                            </div>
                            <div
                                class="p-3 bg-red-500 rounded-lg text-white shadow-sm shadow-red-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- BARU: Transaksi & Denda Periode Ini (INI YANG IKUT FILTER PERIODE) -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-6 relative overflow-hidden hover:shadow-md transition-all duration-200">
                        <div
                            class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-indigo-50 dark:bg-indigo-900/20">
                        </div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    Transaksi
                                    Periode Ini</p>
                                <h3 id="stat-transaksi-periode"
                                    class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
                                    {{ $periodPayload['stats']['total_transaksi_periode'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Denda: <span
                                        id="stat-denda-periode"
                                        class="font-semibold text-indigo-600 dark:text-indigo-400">Rp
                                        {{ number_format($periodPayload['stats']['denda_periode'], 0, ',', '.') }}</span>
                                </p>
                            </div>
                            <div
                                class="p-3 bg-indigo-500 rounded-lg text-white shadow-sm shadow-indigo-200 dark:shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- 2. ANALYTICS — 4 CHART BARU (SEMUA IKUT FILTER PERIODE) --}}
            {{-- Baris 1: Pie Kategori Buku | Donut Status Transaksi     --}}
            {{-- Baris 2: Horizontal Bar Top 10 Buku Terpopuler          --}}
            {{-- Baris 3: Line Chart Trend Peminjaman (modern)           --}}
            {{-- ========================================== --}}

            {{-- Baris 1 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pie Chart: Distribusi Peminjaman per Kategori Buku -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 transition-colors duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-md font-bold text-gray-800 dark:text-gray-100">Peminjaman per Kategori Buku
                        </h3>
                        <span
                            class="px-2 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-bold rounded">Periode
                            Terpilih</span>
                    </div>
                    <div class="relative h-72">
                        <canvas id="chartKategoriBuku"></canvas>
                    </div>
                </div>

                <!-- Donut Chart: Status Transaksi -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 transition-colors duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-md font-bold text-gray-800 dark:text-gray-100">Status Transaksi</h3>
                        <span
                            class="px-2 py-1 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 text-xs font-bold rounded">Periode
                            Terpilih</span>
                    </div>
                    <div class="relative h-72 flex justify-center items-center">
                        <canvas id="chartStatusTransaksi"></canvas>
                        {{-- Teks total transaksi di tengah donut --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span id="donut-total-value"
                                class="text-2xl font-bold text-gray-800 dark:text-gray-100">0</span>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total
                                Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baris 2 --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-100">Top 10 Buku Terpopuler</h3>
                    <span
                        class="px-2 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs font-bold rounded">Periode
                        Terpilih</span>
                </div>
                <div class="relative" style="height: 380px;">
                    <canvas id="chartTopBuku"></canvas>
                </div>
            </div>

            {{-- Baris 3 --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 transition-colors duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-100">Trend Peminjaman vs Pengembalian
                    </h3>
                    <span id="trend-granularity-badge"
                        class="px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded">Harian</span>
                </div>
                <div class="relative h-72">
                    <canvas id="chartTrend"></canvas>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- 4. DAFTAR TRANSAKSI & BUKU TERLAMBAT (dipertahankan, tidak terikat filter) --}}
            {{-- ========================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Transaksi Terbaru -->
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 overflow-hidden transition-colors duration-300">
                    <div
                        class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-md">Transaksi Terbaru</h3>
                            <span
                                class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">5
                                Teratas</span>
                        </div>
                        <a href="{{ route('transaksi.index') }}"
                            class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline flex items-center gap-1 transition-all">
                            Lihat Semua <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50 dark:bg-gray-900/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <th class="px-6 py-3">Kode</th>
                                    <th class="px-6 py-3">Anggota</th>
                                    <th class="px-6 py-3">Buku</th>
                                    <th class="px-6 py-3">Tgl Pinjam</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-300">
                                @forelse($recentTransaksi as $trx)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs font-semibold">
                                            <a href="{{ route('transaksi.show', $trx->id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">{{ $trx->kode_transaksi }}</a>
                                        </td>
                                        <td class="px-6 py-4 font-medium">
                                            <a href="{{ route('anggota.show', $trx->anggota->id) }}"
                                                class="text-gray-800 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">{{ $trx->anggota->nama }}</a>
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">
                                            <a href="{{ route('buku.show', $trx->buku->id) }}"
                                                class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">{{ $trx->buku->judul }}</a>
                                        </td>
                                        <td class="px-6 py-4">{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $trx->status === 'Dipinjam' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' : 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300' }}">
                                                {{ $trx->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">Belum ada
                                            transaksi terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Buku Terlambat -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 overflow-hidden transition-colors duration-300">
                    <div
                        class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800">
                        <h3 class="font-bold text-red-600 dark:text-red-400 text-md">Buku Terlambat</h3>
                        <span
                            class="px-2.5 py-0.5 text-xs font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 rounded-full">{{ $totalTerlambat }}</span>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($listTerlambat as $terlambat)
                            <div
                                class="flex items-start gap-3 p-3 bg-red-50/40 dark:bg-red-900/10 rounded-lg border border-red-100/50 dark:border-red-800/40">
                                <div
                                    class="p-1.5 bg-red-100 dark:bg-red-900/40 rounded-lg text-red-600 dark:text-red-400 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('buku.show', $terlambat->buku->id) }}"
                                        class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate hover:text-red-600 dark:hover:text-red-400 hover:underline block">
                                        {{ $terlambat->buku->judul }}
                                    </a>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                                        Peminjam: <a href="{{ route('anggota.show', $terlambat->anggota->id) }}"
                                            class="font-medium text-gray-800 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">{{ $terlambat->anggota->nama }}</a>
                                    </p>
                                    <p class="text-[10px] text-red-500 dark:text-red-400 font-semibold mt-1">
                                        Jatuh Tempo: {{ $terlambat->tanggal_kembali->format('d/m/Y') }}
                                        ({{ now()->diffInDays($terlambat->tanggal_kembali) }} Hari)
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-10 w-10 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Tidak ada pengembalian
                                    terlambat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- 5. TAMBAHAN DATA BARU & AKTIF (Buku/Anggota Terbaru) --}}
            {{-- ========================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Buku Terbaru -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-5 transition-colors duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800 dark:text-gray-100 text-md flex items-center gap-2">
                            <span class="p-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            Buku Baru Ditambahkan
                        </h3>
                        <a href="{{ route('buku.index') }}"
                            class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline flex items-center gap-1">
                            Lihat Semua <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($bukuTerbaru as $buku)
                            <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                                <div>
                                    <a href="{{ route('buku.show', $buku->id) }}"
                                        class="text-sm font-bold text-gray-800 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-colors block">
                                        {{ $buku->judul }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        Pengarang: <span
                                            class="font-medium text-gray-700 dark:text-gray-300">{{ optional($buku->pengarang)->nama ?? ($buku->pengarang ?? '-') }}</span>
                                    </p>
                                </div>
                                <span
                                    class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full font-medium">Stok:
                                    {{ $buku->stok }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Anggota Terbaru -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-5 transition-colors duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800 dark:text-gray-100 text-md flex items-center gap-2">
                            <span
                                class="p-1 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </span>
                            Anggota Baru Terdaftar
                        </h3>
                        <a href="{{ route('anggota.index') }}"
                            class="text-sm font-semibold text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 hover:underline flex items-center gap-1">
                            Lihat Semua <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($anggotaTerbaru as $anggota)
                            <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                                <div>
                                    <a href="{{ route('anggota.show', $anggota->id) }}"
                                        class="text-sm font-bold text-gray-800 dark:text-gray-100 hover:text-green-600 dark:hover:text-green-400 hover:underline transition-colors block">
                                        {{ $anggota->nama }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                        ID: <span
                                            class="font-medium text-gray-700 dark:text-gray-300">{{ $anggota->id_anggota ?? ($anggota->nomor_anggota ?? ($anggota->kode_anggota ?? $anggota->id)) }}</span>
                                    </p>
                                </div>
                                <span
                                    class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $anggota->status === 'Aktif' ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' }}">
                                    {{ $anggota->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- 6. AKSES AKSI CEPAT / QUICK ACTIONS --}}
            {{-- ========================================== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="h-5 w-1 rounded-full bg-cyan-600"></span>
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Aksi Cepat
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('buku.create') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                        <div
                            class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div class="font-bold text-gray-700 dark:text-gray-300 text-xs">Tambah Buku</div>
                    </a>

                    <a href="{{ route('anggota.create') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                        <div
                            class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="font-bold text-gray-700 dark:text-gray-300 text-xs">Tambah Anggota</div>
                    </a>

                    <a href="{{ route('transaksi.create') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                        <div
                            class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:bg-green-600 group-hover:text-white transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="font-bold text-gray-700 dark:text-gray-300 text-xs">Transaksi Baru</div>
                    </a>

                    <a href="#"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-100 dark:ring-gray-700 p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                        <div
                            class="mx-auto mb-3 flex items-center justify-center h-11 w-11 rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="font-bold text-gray-700 dark:text-gray-300 text-xs">Pengaturan</div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ==================================================================
                // DARK MODE: baca tema aktif & siapkan palet warna teks/grid Chart.js
                // yang menyesuaikan, karena Chart.js menggambar di <canvas> dan TIDAK
                // otomatis ikut class "dark" Tailwind — warnanya harus diberikan manual.
                // ==================================================================
                function isDarkMode() {
                    return document.documentElement.classList.contains('dark');
                }

                function chartTheme() {
                    return isDarkMode() ? {
                        text: '#d1d5db', // gray-300
                        grid: '#374151', // gray-700
                        cardBg: '#1f2937', // gray-800
                        valueLabel: '#e5e7eb' // gray-200
                    } : {
                        text: '#374151', // gray-700
                        grid: '#f3f4f6', // gray-100
                        cardBg: '#ffffff',
                        valueLabel: '#374151'
                    };
                }

                // ==================================================================
                // DATA AWAL (dari PHP) — dipakai untuk render pertama kali,
                // supaya chart langsung muncul tanpa perlu 1x AJAX call ekstra.
                // ==================================================================
                const initialPayload = @json($periodPayload);
                const routeDashboardData = @json(route('dashboard.data'));

                // Palet warna konsisten dipakai di semua chart
                const PALETTE = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899',
                    '#84cc16', '#f97316', '#14b8a6'
                ];

                const labelGranularitas = {
                    daily: 'Harian',
                    weekly: 'Mingguan',
                    monthly: 'Bulanan',
                    yearly: 'Tahunan'
                };

                // Peta period -> granularitas (harus sinkron dengan resolvePeriodRange() di controller)
                const periodToGranularity = {
                    '7hari': 'daily',
                    '30hari': 'daily',
                    '3bulan': 'weekly',
                    '6bulan': 'monthly',
                    '1tahun': 'monthly',
                    'semua': 'yearly'
                };

                // ==================================================================
                // PLUGIN CUSTOM: menampilkan angka di ujung batang untuk horizontal bar
                // (tidak butuh chartjs-plugin-datalabels tambahan)
                // ==================================================================
                const barValueLabelPlugin = {
                    id: 'barValueLabel',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx
                        } = chart;
                        chart.data.datasets.forEach((dataset, dsIndex) => {
                            const meta = chart.getDatasetMeta(dsIndex);
                            meta.data.forEach((bar, index) => {
                                const value = dataset.data[index];
                                ctx.save();
                                ctx.fillStyle = chartTheme().valueLabel;
                                ctx.font = 'bold 11px sans-serif';
                                ctx.textAlign = 'left';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(value, bar.x + 6, bar.y);
                                ctx.restore();
                            });
                        });
                    }
                };

                // ==================================================================
                // INISIALISASI CHART (dibuat sekali; selanjutnya di-update via .data + .update())
                // ==================================================================
                let chartKategori, chartStatus, chartTopBuku, chartTrend;
                let lastPayload = initialPayload; // payload chart yang terakhir dirender, dipakai saat re-render tema

                function initCharts(payload) {
                    lastPayload = payload;
                    const theme = chartTheme();

                    // --- 1. PIE: Kategori Buku ---
                    chartKategori = new Chart(document.getElementById('chartKategoriBuku').getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: payload.charts.kategoriBuku.labels,
                            datasets: [{
                                data: payload.charts.kategoriBuku.data,
                                backgroundColor: PALETTE,
                                borderWidth: 2,
                                borderColor: theme.cardBg
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 8,
                                        color: theme.text,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                            const pct = total > 0 ? ((ctx.parsed / total) * 100)
                                                .toFixed(1) : 0;
                                            return `${ctx.label}: ${ctx.parsed} buku dipinjam (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // --- 2. DONUT: Status Transaksi ---
                    chartStatus = new Chart(document.getElementById('chartStatusTransaksi').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: payload.charts.statusTransaksi.labels,
                            datasets: [{
                                data: payload.charts.statusTransaksi.data,
                                backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                                borderWidth: 2,
                                borderColor: theme.cardBg
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '72%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 8,
                                        color: theme.text,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                            const pct = total > 0 ? ((ctx.parsed / total) * 100)
                                                .toFixed(1) : 0;
                                            return `${ctx.label}: ${ctx.parsed} transaksi (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    document.getElementById('donut-total-value').textContent = payload.charts.statusTransaksi
                        .total;

                    // --- 3. HORIZONTAL BAR: Top 10 Buku ---
                    chartTopBuku = new Chart(document.getElementById('chartTopBuku').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: payload.charts.topBuku.labels,
                            datasets: [{
                                label: 'Jumlah Dipinjam',
                                data: payload.charts.topBuku.data,
                                backgroundColor: '#6366f1',
                                borderRadius: 4,
                                barThickness: 18
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    right: 30
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            return `${ctx.parsed.x} kali dipinjam`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        color: theme.text
                                    },
                                    grid: {
                                        color: theme.grid
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: theme.text,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            }
                        },
                        plugins: [barValueLabelPlugin]
                    });

                    // --- 4. LINE: Trend Peminjaman vs Pengembalian (modern) ---
                    const trendCtx = document.getElementById('chartTrend').getContext('2d');
                    const gradientPinjam = trendCtx.createLinearGradient(0, 0, 0, 280);
                    gradientPinjam.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
                    gradientPinjam.addColorStop(1, 'rgba(99, 102, 241, 0)');

                    const gradientKembali = trendCtx.createLinearGradient(0, 0, 0, 280);
                    gradientKembali.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
                    gradientKembali.addColorStop(1, 'rgba(16, 185, 129, 0)');

                    chartTrend = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: payload.charts.trend.labels,
                            datasets: [{
                                    label: 'Peminjaman',
                                    data: payload.charts.trend.pinjam,
                                    borderColor: '#6366f1',
                                    backgroundColor: gradientPinjam,
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 2.5,
                                    pointRadius: 3,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#6366f1',
                                    pointBorderColor: theme.cardBg,
                                    pointBorderWidth: 2
                                },
                                {
                                    label: 'Pengembalian',
                                    data: payload.charts.trend.kembali,
                                    borderColor: '#10b981',
                                    backgroundColor: gradientKembali,
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 2,
                                    pointRadius: 3,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#10b981',
                                    pointBorderColor: theme.cardBg,
                                    pointBorderWidth: 2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 600,
                                easing: 'easeOutQuart'
                            },
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 6,
                                        color: theme.text,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: theme.grid
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        color: theme.text
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: theme.text
                                    }
                                }
                            }
                        }
                    });

                    updateGranularityBadge(payload.period);
                }

                function updateGranularityBadge(period) {
                    const gran = periodToGranularity[period] || 'daily';
                    document.getElementById('trend-granularity-badge').textContent = labelGranularitas[
                        gran];
                }

                // ==================================================================
                // UPDATE CHART & CARD YANG SUDAH ADA (dipanggil setiap ganti filter)
                // ==================================================================
                function updateCharts(payload) {
                    lastPayload = payload;
                    chartKategori.data.labels = payload.charts.kategoriBuku.labels;
                    chartKategori.data.datasets[0].data = payload.charts.kategoriBuku.data;
                    chartKategori.update();

                    chartStatus.data.labels = payload.charts.statusTransaksi.labels;
                    chartStatus.data.datasets[0].data = payload.charts.statusTransaksi.data;
                    chartStatus.update();
                    document.getElementById('donut-total-value').textContent = payload.charts
                        .statusTransaksi.total;

                    chartTopBuku.data.labels = payload.charts.topBuku.labels;
                    chartTopBuku.data.datasets[0].data = payload.charts.topBuku.data;
                    chartTopBuku.update();

                    chartTrend.data.labels = payload.charts.trend.labels;
                    chartTrend.data.datasets[0].data = payload.charts.trend.pinjam;
                    chartTrend.data.datasets[1].data = payload.charts.trend.kembali;
                    chartTrend.update();

                    updateGranularityBadge(payload.period);

                    // Update 2 statistik card yang mengikuti filter periode
                    document.getElementById('stat-transaksi-periode').textContent = payload.stats
                        .total_transaksi_periode;
                    document.getElementById('stat-denda-periode').textContent = 'Rp ' + Number(payload.stats
                        .denda_periode).toLocaleString('id-ID');
                }

                // ==================================================================
                // FILTER PERIODE: tombol + fetch AJAX (tanpa reload halaman)
                // ==================================================================
                const periodContainer = document.getElementById('period-filter');
                const periodButtons = periodContainer.querySelectorAll('.period-btn');
                const loadingIndicator = document.getElementById('period-loading');
                const exportPdfLink = document.getElementById('btn-export-pdf');
                const routeDashboardExport = @json(route('dashboard.export'));

                function setActiveButton(period) {
                    periodButtons.forEach(btn => {
                        const isActive = btn.dataset.period === period;
                        btn.classList.toggle('bg-indigo-600', isActive);
                        btn.classList.toggle('text-white', isActive);
                        btn.classList.toggle('border-indigo-600', isActive);
                        btn.classList.toggle('bg-white', !isActive);
                        btn.classList.toggle('dark:bg-gray-700', !isActive);
                        btn.classList.toggle('text-gray-600', !isActive);
                        btn.classList.toggle('dark:text-gray-300', !isActive);
                        btn.classList.toggle('border-gray-200', !isActive);
                        btn.classList.toggle('dark:border-gray-600', !isActive);
                    });

                    // Samakan link Export PDF dengan periode yang sedang aktif,
                    // supaya laporan yang di-download selalu sesuai apa yang dilihat di layar.
                    if (exportPdfLink) {
                        exportPdfLink.href = `${routeDashboardExport}?period=${encodeURIComponent(period)}`;
                    }
                }

                function fetchAndUpdate(period) {
                    loadingIndicator.classList.remove('hidden');
                    loadingIndicator.classList.add('flex');

                    fetch(`${routeDashboardData}?period=${encodeURIComponent(period)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal memuat data dashboard');
                            return res.json();
                        })
                        .then(payload => {
                            updateCharts(payload);
                            periodContainer.dataset.current = period;
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal memuat data untuk periode ini. Silakan coba lagi.');
                        })
                        .finally(() => {
                            loadingIndicator.classList.add('hidden');
                            loadingIndicator.classList.remove('flex');
                        });
                }

                periodButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const period = this.dataset.period;
                        if (period === periodContainer.dataset.current) return; // sudah aktif, skip
                        setActiveButton(period);
                        fetchAndUpdate(period);
                    });
                });

                // ==================================================================
                // JALANKAN SAAT HALAMAN DIMUAT
                // ==================================================================
                setActiveButton(periodContainer.dataset.current);
                initCharts(initialPayload);

                // ==================================================================
                // DARK MODE: re-render ulang semua chart saat tema di-toggle,
                // supaya warna teks/grid chart ikut menyesuaikan seketika
                // (Chart.js tidak bisa dikontrol lewat CSS/class biasa).
                // ==================================================================
                document.addEventListener('click', function(e) {
                    const toggle = e.target.closest('[aria-label="Toggle dark mode"]');
                    if (!toggle) return;

                    // Tunggu 1 tick agar class "dark" di <html> sudah ter-update
                    // oleh Alpine sebelum chart dibaca ulang warnanya.
                    setTimeout(() => {
                        chartKategori.destroy();
                        chartStatus.destroy();
                        chartTopBuku.destroy();
                        chartTrend.destroy();
                        // Pakai payload terakhir yang sudah ditampilkan (bukan payload awal),
                        // supaya data periode yang sedang dilihat tetap sama setelah re-render.
                        initCharts(lastPayload);
                    }, 50);
                });
            });
        </script>
    @endpush
</x-app-layout>
