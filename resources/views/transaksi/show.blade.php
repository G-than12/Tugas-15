<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Detail Transaksi
            </h2>
            <nav class="text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:underline">Home</a> /
                <a href="{{ route('transaksi.index') }}" class="hover:underline">Transaksi</a> /
                {{ $transaksi->kode_transaksi }}
            </nav>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div id="alert-flash"
                    class="bg-green-100 dark:bg-green-900/40 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded text-sm transition-opacity duration-500">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="alert-flash"
                    class="bg-red-100 dark:bg-red-900/40 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded text-sm transition-opacity duration-500">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ALERT KETERLAMBATAN --}}
            @if (
                $transaksi->status === 'Dipinjam' &&
                    \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($transaksi->tanggal_kembali)->startOfDay()))
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                Peringatan Keterlambatan
                            </h3>
                            <div class="mt-1 text-sm text-red-700 dark:text-red-400">
                                <p>Waktu peminjaman buku ini telah melewati batas jatuh tempo
                                    ({{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d F Y') }}).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- END ALERT KETERLAMBATAN --}}

            {{-- ==================================================== --}}
            {{-- BARIS 1: Info Buku (kiri) + QR Code & Status (kanan) --}}
            {{-- ==================================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Card Buku Terpadu --}}
                <div
                    class="md:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex gap-6 items-center transition-colors duration-300">
                    <div
                        class="w-28 h-36 sm:w-32 sm:h-40 flex-shrink-0 rounded-xl flex justify-center items-center
                        @if ($transaksi->buku->kategori == 'Programming') bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
                        @elseif($transaksi->buku->kategori == 'Database') bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400
                        @elseif($transaksi->buku->kategori == 'Web Design') bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400
                        @elseif($transaksi->buku->kategori == 'Networking') bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400
                        @elseif($transaksi->buku->kategori == 'Data Science') bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400
                        @else bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 @endif
                    ">
                        @switch($transaksi->buku->kategori ?? null)
                            @case('Programming')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                                </svg>
                            @break

                            @case('Database')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                            @break

                            @case('Web Design')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.879-5.831a1.5 1.5 0 00-1.4-2.27h-3.382a2.75 2.75 0 00-2.75 2.75v3.381a1.5 1.5 0 002.27 1.4l5.83-3.88a15.994 15.994 0 00-4.647 4.764z" />
                                </svg>
                            @break

                            @case('Networking')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            @break

                            @case('Data Science')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            @break

                            @default
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-12 h-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                        @endswitch
                    </div>

                    <div class="min-w-0 flex-1">
                        @if ($transaksi->buku)
                            <span
                                class="inline-block mb-1 px-2 py-1 text-xs font-semibold rounded-full
                                @if ($transaksi->buku->kategori == 'Programming') bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300
                                @elseif($transaksi->buku->kategori == 'Database') bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300
                                @elseif($transaksi->buku->kategori == 'Web Design') bg-pink-100 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300
                                @elseif($transaksi->buku->kategori == 'Networking') bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300
                                @elseif($transaksi->buku->kategori == 'Data Science') bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 @endif
                            ">
                                {{ $transaksi->buku->kategori }}
                            </span>
                        @endif

                        <h3 class="font-bold text-lg sm:text-xl text-gray-800 dark:text-gray-100 truncate"
                            title="{{ $transaksi->buku->judul ?? '-' }}">
                            {{ $transaksi->buku->judul ?? '-' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            Ditulis oleh: <span
                                class="font-medium text-gray-700 dark:text-gray-300">{{ $transaksi->buku->pengarang ?? '-' }}</span>
                        </p>

                        {{-- Metadata Buku (Kode, ISBN, Terbit) --}}
                        <div
                            class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div>
                                <span class="block text-xs uppercase tracking-wider">Kode Buku</span>
                                <span
                                    class="font-semibold text-gray-800 dark:text-gray-200">{{ $transaksi->buku->kode_buku ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs uppercase tracking-wider">ISBN</span>
                                <span
                                    class="font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->buku->isbn ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs uppercase tracking-wider">Tahun Terbit</span>
                                <span
                                    class="font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->buku->tahun_terbit ?? '-' }}</span>
                            </div>
                        </div>

                        <a href="{{ route('buku.show', $transaksi->buku_id) }}"
                            class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            Lihat detail buku
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- QR Code + Status + Aksi --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 flex flex-col items-center justify-between transition-colors duration-300">
                    <div class="bg-white p-2 rounded-lg border border-gray-100 dark:border-gray-700">
                        {!! \App\Helpers\QrCodeHelper::generateSvg($transaksi->kode_transaksi, 120) !!}
                    </div>

                    <div class="text-center mt-3">
                        <p class="font-mono text-sm font-bold text-gray-700 dark:text-gray-300">
                            {{ $transaksi->kode_transaksi }}</p>
                        @if ($transaksi->status == 'Dipinjam')
                            <span
                                class="inline-block mt-1 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 px-3 py-1 rounded-full text-xs font-medium border border-yellow-200 dark:border-yellow-800">
                                Dipinjam
                            </span>
                        @else
                            <span
                                class="inline-block mt-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-xs font-medium border border-green-200 dark:border-green-800">
                                Dikembalikan
                            </span>
                        @endif
                    </div>

                    <div class="w-full flex flex-col gap-2 mt-4">
                        @if ($transaksi->status === 'Dipinjam')
                            <button type="button" id="btn-kembalikan"
                                class="w-full bg-blue-600 text-white font-medium px-4 py-2 rounded-lg text-sm hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all">
                                Tandai Dikembalikan
                            </button>
                            <form action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST"
                                id="form-kembalikan">
                                @csrf
                            </form>
                        @endif

                        <a href="{{ route('transaksi.index') }}"
                            class="w-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-center font-medium px-4 py-2 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            {{-- ==================================================== --}}
            {{-- BARIS 2: Data Anggota & Informasi Transaksi --}}
            {{-- ==================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Data Anggota --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 transition-colors duration-300">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h6 class="font-semibold text-gray-700 dark:text-gray-200">Informasi Peminjam (Anggota)</h6>
                    </div>

                    <div class="space-y-3">
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nomor Anggota</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->anggota->kode_anggota ?? '-' }}</span>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                            <a href="{{ route('anggota.show', $transaksi->anggota_id) }}"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $transaksi->anggota->nama ?? '-' }}
                            </a>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->anggota->email ?? '-' }}</span>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nomor Telepon</span>
                            <span
                                class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->anggota->telepon ?? '-' }}</span>
                        </div>
                        @if (isset($transaksi->anggota->status))
                            <div class="flex flex-col sm:flex-row sm:justify-between py-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status Keanggotaan</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->anggota->status }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Informasi Transaksi --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 transition-colors duration-300">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h6 class="font-semibold text-gray-700 dark:text-gray-200">Detail Peminjaman</h6>
                    </div>

                    @php
                        $tglPinjam = \Carbon\Carbon::parse($transaksi->tanggal_pinjam);
                        $tglKembali = \Carbon\Carbon::parse($transaksi->tanggal_kembali);
                        $lamaPinjam = $tglPinjam->diffInDays($tglKembali);

                        $isTerlambat =
                            $transaksi->status === 'Dipinjam' &&
                            \Carbon\Carbon::now()
                                ->startOfDay()
                                ->gt($tglKembali->copy()->startOfDay());

                        if ($transaksi->status === 'Dikembalikan' && $transaksi->tanggal_dikembalikan) {
                            $hariTerlambat = (int) max(
                                0,
                                $tglKembali->diffInDays(\Carbon\Carbon::parse($transaksi->tanggal_dikembalikan), false),
                            );
                        } elseif ($transaksi->status === 'Dipinjam') {
                            $hariTerlambat = (int) max(0, $tglKembali->diffInDays(\Carbon\Carbon::now(), false));
                        } else {
                            $hariTerlambat = 0;
                        }
                    @endphp

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tanggal Pinjam</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">
                                {{ $tglPinjam->format('d M Y') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jatuh Tempo</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">
                                {{ $tglKembali->format('d M Y') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Lama Pinjam</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $lamaPinjam }} hari</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tgl Dikembalikan</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">
                                {{ $transaksi->tanggal_dikembalikan ? \Carbon\Carbon::parse($transaksi->tanggal_dikembalikan)->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div
                            class="{{ $hariTerlambat > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }} p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Keterlambatan</p>
                            <p
                                class="font-semibold {{ $hariTerlambat > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-100' }}">
                                {{ $hariTerlambat > 0 ? $hariTerlambat . ' hari' : 'Tepat Waktu' }}
                            </p>
                        </div>
                        <div
                            class="{{ $transaksi->denda > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }} p-3 rounded-lg">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Denda</p>
                            <p
                                class="font-semibold {{ $transaksi->denda > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-100' }}">
                                {{ $transaksi->denda > 0 ? 'Rp ' . number_format($transaksi->denda, 0, ',', '.') : '-' }}
                            </p>
                        </div>
                    </div>

                    @if ($transaksi->keterangan)
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-sm">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Keterangan / Catatan</p>
                            <p class="text-gray-800 dark:text-gray-100 italic">"{{ $transaksi->keterangan }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ==================================================== --}}
            {{-- BARIS 3: Timeline --}}
            {{-- ==================================================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 transition-colors duration-300">
                <h6 class="font-semibold text-gray-700 dark:text-gray-200 mb-6">Timeline Peminjaman</h6>

                <ol class="relative border-s-2 border-gray-200 dark:border-gray-700 ms-3">
                    {{-- Step 1: Dipinjam --}}
                    <li class="mb-8 ms-6">
                        <span
                            class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 dark:bg-blue-900/40 rounded-full -start-3 ring-4 ring-white dark:ring-gray-800">
                            <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Buku Dipinjam</h3>
                        <time class="block text-xs text-gray-400 dark:text-gray-500 mb-1">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d F Y') }}
                        </time>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Transaksi {{ $transaksi->kode_transaksi }} dibuat, batas pengembalian ditetapkan
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d F Y') }}.
                        </p>
                    </li>

                    {{-- Step 2: Jatuh Tempo / Peringatan Keterlambatan --}}
                    <li class="mb-8 ms-6">
                        <span
                            class="absolute flex items-center justify-center w-6 h-6
                            {{ $isTerlambat ? 'bg-red-100 dark:bg-red-900/40' : 'bg-gray-100 dark:bg-gray-700' }}
                            rounded-full -start-3 ring-4 ring-white dark:ring-gray-800">
                            <svg class="w-3 h-3 {{ $isTerlambat ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Jatuh Tempo</h3>
                        <time class="block text-xs text-gray-400 dark:text-gray-500 mb-1">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d F Y') }}
                        </time>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            @if ($isTerlambat)
                                Batas waktu pengembalian telah terlewati dan buku belum dikembalikan.
                            @elseif ($transaksi->status === 'Dipinjam')
                                Batas waktu pengembalian belum terlewati.
                            @else
                                Batas waktu pengembalian tercatat pada tanggal ini.
                            @endif
                        </p>
                    </li>

                    {{-- Step 3: Dikembalikan --}}
                    <li class="ms-6">
                        <span
                            class="absolute flex items-center justify-center w-6 h-6
                            {{ $transaksi->status === 'Dikembalikan' ? 'bg-green-100 dark:bg-green-900/40' : 'bg-gray-100 dark:bg-gray-700' }}
                            rounded-full -start-3 ring-4 ring-white dark:ring-gray-800">
                            <svg class="w-3 h-3 {{ $transaksi->status === 'Dikembalikan' ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Buku Dikembalikan</h3>
                        <time class="block text-xs text-gray-400 dark:text-gray-500 mb-1">
                            {{ $transaksi->tanggal_dikembalikan ? \Carbon\Carbon::parse($transaksi->tanggal_dikembalikan)->format('d F Y') : 'Belum dikembalikan' }}
                        </time>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            @if ($transaksi->status === 'Dikembalikan')
                                Buku telah dikembalikan.
                                @if ($transaksi->denda > 0)
                                    Denda keterlambatan sebesar Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                    dikenakan.
                                @else
                                    Tidak ada denda keterlambatan.
                                @endif
                            @else
                                Menunggu pengembalian buku oleh anggota.
                            @endif
                        </p>
                    </li>
                </ol>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            const alertFlash = document.getElementById('alert-flash');
            if (alertFlash) {
                setTimeout(() => {
                    alertFlash.style.opacity = '0';
                    setTimeout(() => alertFlash.remove(), 500);
                }, 5000);
            }
        </script>
    @endpush

    @if ($transaksi->status === 'Dipinjam')
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.getElementById('btn-kembalikan').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Tandai Dikembalikan?',
                        text: `Buku "{{ $transaksi->buku->judul ?? '' }}" akan ditandai sudah dikembalikan.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Kembalikan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-kembalikan').submit();
                        }
                    });
                });
            </script>
        @endpush
    @endif
</x-app-layout>
