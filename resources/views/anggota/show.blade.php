<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Detail Anggota
            </h2>
            <nav class="text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('dashboard') }}" class="hover:underline">Home</a> /
                <a href="{{ route('anggota.index') }}" class="hover:underline">Anggota</a> /
                {{ $anggota->nama }}
            </nav>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- 1. PROFILE CARD                                               --}}
            {{-- ============================================================ --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center gap-6">

                    {{-- Avatar --}}
                    <div class="flex-shrink-0 mx-auto md:mx-0">
                        <div
                            class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold shadow-inner ring-4 ring-blue-50 dark:ring-gray-700">
                            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                        </div>
                    </div>

                    {{-- Info utama --}}
                    <div class="flex-1 text-center md:text-left">
                        <div
                            class="flex flex-col md:flex-row md:items-center gap-3 justify-center md:justify-start mb-1">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $anggota->nama }}</h3>
                            @if ($anggota->status == 'Aktif')
                                <span
                                    class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-full text-xs font-medium w-fit mx-auto md:mx-0 border border-emerald-200 dark:border-emerald-500/20">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full text-xs font-medium w-fit mx-auto md:mx-0 border border-gray-200 dark:border-gray-600">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-400 mt-1">
                            <code
                                class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md text-gray-600 dark:text-gray-300 font-mono tracking-wide">{{ $anggota->kode_anggota }}</code>
                        </p>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 mt-5 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-2.5 justify-center md:justify-start">
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                {{ $anggota->email ?: '-' }}
                            </div>
                            <div class="flex items-center gap-2.5 justify-center md:justify-start">
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.414-5.312-3.83-6.724-6.724l1.292-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                                {{ $anggota->telepon ?: '-' }}
                            </div>
                            <div class="flex items-center gap-2.5 justify-center md:justify-start">
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                {{ $anggota->alamat ?: '-' }}
                            </div>
                            <div class="flex items-center gap-2.5 justify-center md:justify-start">
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                Bergabung {{ $anggota->tanggal_daftar->format('d F Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-row md:flex-col gap-3 justify-center mt-6 md:mt-0">
                        <a href="{{ route('anggota.edit', $anggota->id) }}"
                            class="flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 shadow-sm transition text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('anggota.index') }}"
                            class="flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 shadow-sm px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>
                            Kembali
                        </a>
                        <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                            id="form-delete-anggota" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="button" id="btn-delete-anggota"
                                class="flex w-full items-center justify-center gap-2 bg-red-600 hover:bg-red-700 shadow-sm transition text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Detail tambahan (biodata lengkap) --}}
                <details class="mt-8 pt-5 border-t border-gray-100 dark:border-gray-700 group">
                    <summary
                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 cursor-pointer select-none flex items-center gap-2 hover:text-gray-700 dark:hover:text-gray-200 transition">
                        <svg class="w-4 h-4 group-open:rotate-180 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                        Detail Biodata Lengkap
                    </summary>
                    <div
                        class="mt-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-600/50">
                        <table class="w-full text-sm">
                            <tr class="border-b border-gray-200 dark:border-gray-700/50">
                                <td class="py-2.5 font-medium text-gray-500 dark:text-gray-400 w-48">Tanggal Lahir</td>
                                <td class="py-2.5 text-gray-800 dark:text-gray-300">:
                                    {{ $anggota->tanggal_lahir->format('d F Y') }}
                                    <span class="text-gray-400">({{ $anggota->umur }} tahun)</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700/50">
                                <td class="py-2.5 font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</td>
                                <td class="py-2.5 text-gray-800 dark:text-gray-300">: {{ $anggota->jenis_kelamin }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700/50">
                                <td class="py-2.5 font-medium text-gray-500 dark:text-gray-400">Pekerjaan</td>
                                <td class="py-2.5 text-gray-800 dark:text-gray-300">: {{ $anggota->pekerjaan ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-medium text-gray-500 dark:text-gray-400">Lama Menjadi Anggota
                                </td>
                                <td class="py-2.5 text-gray-800 dark:text-gray-300">: {{ $anggota->lama_anggota }}
                                    hari</td>
                            </tr>
                        </table>
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between text-xs text-gray-400 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700/50 gap-2">
                            <span>Ditambahkan: {{ $anggota->created_at->format('d M Y H:i') }}</span>
                            <span>Terakhir Update: {{ $anggota->updated_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </details>
            </div>

            {{-- ============================================================ --}}
            {{-- 2. STATISTIK CARDS                                            --}}
            {{-- ============================================================ --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2.5 text-blue-500 mb-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total
                            Pinjam</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        {{ $statistik['total_peminjaman'] }}</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2.5 text-emerald-500 mb-3">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kembali</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        {{ $statistik['total_pengembalian'] }}</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2.5 text-blue-400 mb-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-400/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dipinjam</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $statistik['sedang_dipinjam'] }}
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2.5 text-amber-500 mb-3">
                        <div class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Terlambat</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $statistik['total_terlambat'] }}
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2.5 text-red-500 mb-3">
                        <div class="p-2 bg-red-50 dark:bg-red-500/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total
                            Denda</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800 dark:text-gray-100">Rp
                        {{ number_format($statistik['total_denda'], 0, ',', '.') }}</p>
                    @if ($statistik['estimasi_denda_berjalan'] > 0)
                        <p class="text-xs text-amber-500 mt-1 font-medium">+Rp
                            {{ number_format($statistik['estimasi_denda_berjalan'], 0, ',', '.') }} jalan</p>
                    @endif
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2.5 text-purple-500 mb-3">
                        <div class="p-2 bg-purple-50 dark:bg-purple-500/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Buku
                            Favorit</span>
                    </div>
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100 truncate"
                        title="{{ $statistik['buku_favorit']->judul ?? '-' }}">
                        {{ $statistik['buku_favorit']->judul ?? '-' }}
                    </p>
                    @if ($statistik['buku_favorit'])
                        <p class="text-xs text-gray-400 mt-1">Dipinjam {{ $statistik['buku_favorit_jumlah'] }}x</p>
                    @endif
                </div>
            </div>

            {{-- Statistik tambahan (baris kedua) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Hari Meminjam</span>
                    <span
                        class="font-semibold text-gray-800 dark:text-gray-100">{{ $statistik['total_hari_meminjam'] }}
                        hari</span>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Lama Meminjam</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $statistik['rata_rata_hari'] }}
                        hari</span>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Peminjaman Tahun Ini</span>
                    <span
                        class="font-semibold text-gray-800 dark:text-gray-100">{{ $statistik['peminjaman_tahun_ini'] }}</span>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- 3. INSIGHT TAMBAHAN                                           --}}
            {{-- ============================================================ --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    Insight Anggota
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        <p class="text-gray-500 dark:text-gray-400 mb-1">Kategori Favorit</p>
                        <p class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                            {{ $insight['kategori_favorit'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        <p class="text-gray-500 dark:text-gray-400 mb-1">Kembali Tepat Waktu</p>
                        <p class="font-semibold text-lg text-emerald-600">{{ $insight['persen_tepat_waktu'] }}%</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        <p class="text-gray-500 dark:text-gray-400 mb-1">Tingkat Keterlambatan</p>
                        <p class="font-semibold text-lg text-amber-600">{{ $insight['persen_terlambat'] }}%</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                        <p class="text-gray-500 dark:text-gray-400 mb-1">Lama Menjadi Anggota</p>
                        <p class="font-semibold text-lg text-gray-800 dark:text-gray-100">
                            {{ $insight['lama_menjadi_anggota'] }} <span
                                class="text-sm font-normal text-gray-500">hari</span></p>
                    </div>
                </div>
                @if ($insight['aktivitas_terakhir'])
                    <div
                        class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Aktivitas terakhir: meminjam <span
                            class="font-medium text-gray-700 dark:text-gray-200">"{{ $insight['aktivitas_terakhir']->buku->judul ?? '-' }}"</span>
                        pada {{ $insight['aktivitas_terakhir']->tanggal_pinjam->format('d F Y') }}
                    </div>
                @endif
            </div>

            {{-- ============================================================ --}}
            {{-- 4. RIWAYAT PEMINJAMAN (Filter + Search + Sort + Tabel)        --}}
            {{-- ============================================================ --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200">Riwayat Peminjaman</h4>

                    <form method="GET" action="{{ route('anggota.show', $anggota->id) }}"
                        class="flex flex-col sm:flex-row gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari buku / kode..."
                            class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">

                        <select name="sort" onchange="this.form.submit()"
                            class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>
                                Terbaru</option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama
                            </option>
                            <option value="denda" {{ request('sort') == 'denda' ? 'selected' : '' }}>Denda Terbesar
                            </option>
                            <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>Nama Buku A-Z
                            </option>
                        </select>

                        <input type="hidden" name="status" value="{{ request('status') }}">

                        <button type="submit"
                            class="bg-gray-800 dark:bg-blue-600 hover:bg-gray-700 dark:hover:bg-blue-700 transition text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
                            Terapkan
                        </button>
                        @if (request('search') || request('status') || request('sort'))
                            <a href="{{ route('anggota.show', $anggota->id) }}"
                                class="text-sm text-red-500 hover:text-red-700 self-center font-medium ml-1">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Filter status (button group) --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    @php
                        $statusOptions = [
                            'Semua' => null,
                            'Dipinjam' => 'Dipinjam',
                            'Dikembalikan' => 'Dikembalikan',
                            'Terlambat' => 'Terlambat',
                        ];
                        $currentStatus = request('status', 'Semua');
                    @endphp
                    @foreach ($statusOptions as $label => $value)
                        <a href="{{ route('anggota.show', array_merge([$anggota->id], request()->except('status', 'page'), $value ? ['status' => $value] : [])) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-medium border transition
                                {{ $currentStatus == $label
                                    ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                    : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                {{-- Tabel Riwayat --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 font-medium">No</th>
                                <th class="px-4 py-3 font-medium">Kode</th>
                                <th class="px-4 py-3 font-medium">Judul Buku</th>
                                <th class="px-4 py-3 font-medium">Kategori</th>
                                <th class="px-4 py-3 font-medium">Pinjam</th>
                                <th class="px-4 py-3 font-medium">Kembali</th>
                                <th class="px-4 py-3 font-medium">Durasi</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Denda</th>
                                <th class="px-4 py-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($riwayat as $index => $t)
                                @php
                                    $isTerlambat = $t->terlambat > 0;
                                @endphp
                                <tr
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                                    <td class="px-4 py-3 text-gray-500">{{ $riwayat->firstItem() + $index }}</td>
                                    <td class="px-4 py-3">
                                        <code
                                            class="text-[11px] font-mono tracking-wide bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">{{ $t->kode_transaksi }}</code>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                                        {{ $t->buku->judul ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $t->buku->kategori ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $t->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $t->tanggal_kembali->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $t->durasi_peminjaman }} hari</td>
                                    <td class="px-4 py-3">
                                        @if ($t->status === 'Dipinjam' && $isTerlambat)
                                            <span
                                                class="inline-flex items-center bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full text-xs font-medium border border-amber-200 whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Terlambat
                                            </span>
                                        @elseif ($t->status === 'Dipinjam')
                                            <span
                                                class="inline-flex items-center bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium border border-blue-200 whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                                Dipinjam
                                            </span>
                                        @elseif ($isTerlambat)
                                            <span
                                                class="inline-flex items-center bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full text-xs font-medium border border-orange-200 whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Telat Kembali
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-medium border border-emerald-200 whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Dikembalikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                        @if ($t->estimasi_denda > 0)
                                            <span class="text-red-600 font-medium">Rp
                                                {{ number_format($t->estimasi_denda, 0, ',', '.') }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="#"
                                            class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-medium">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10"
                                        class="px-4 py-8 text-center text-gray-400 bg-white dark:bg-gray-800">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        Belum ada riwayat peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $riwayat->links() }}
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- 5. TIMELINE AKTIVITAS                                         --}}
            {{-- ============================================================ --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Timeline Aktivitas
                </h4>

                @if (count($timeline) > 0)
                    <div class="relative pl-6">
                        <div
                            class="absolute left-[7px] top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700 rounded-full">
                        </div>

                        <div class="space-y-6">
                            @foreach ($timeline as $event)
                                @php
                                    $colorMap = [
                                        'blue' => 'bg-blue-500',
                                        'emerald' => 'bg-emerald-500',
                                        'amber' => 'bg-amber-500',
                                        'red' => 'bg-red-500',
                                    ];
                                    $dotColor = $colorMap[$event['warna']] ?? 'bg-gray-400';
                                @endphp
                                <div class="relative">
                                    <span
                                        class="absolute -left-[27.5px] top-1 w-3.5 h-3.5 rounded-full {{ $dotColor }} ring-4 ring-white dark:ring-gray-800"></span>
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm border border-gray-100 dark:border-gray-600">
                                            {{-- Ganti otomatis emoji menjadi ikon SVG Tailwind --}}
                                            @switch($event['icon'])
                                                @case('📚')
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                                    </svg>
                                                @break

                                                @case('✅')
                                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @break

                                                @case('⚠️')
                                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                @break

                                                @case('💰')
                                                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @break

                                                @default
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                            @endswitch
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-400 mb-0.5">
                                                {{ $event['tanggal']->format('d F Y') }}</p>
                                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                                {{ $event['judul'] }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $event['deskripsi'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-center text-gray-400 py-8">Belum ada aktivitas tercatat.</p>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('btn-delete-anggota').addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus Anggota?',
                    text: `Anggota "{{ $anggota->nama }}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'rounded-lg',
                        cancelButton: 'rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-delete-anggota').submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
