<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Daftar Transaksi Peminjaman
            </h2>
            <div class="flex gap-2">
                <form action="{{ route('transaksi.laporan.export') }}" method="GET" id="form-export-header">
                    {{-- Menyimpan parameter filter saat ini agar file PDF yang terunduh juga terfilter --}}
                    @foreach (request()->except('page') as $key => $value)
                        @if ($value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                </form>
                <button type="submit" form="form-export-header"
                    class="bg-red-600 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export PDF
                </button>
                <a href="{{ route('transaksi.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded text-sm flex items-center gap-1">
                    <span>+</span> Pinjam Buku
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div id="alert-success"
                    class="bg-green-100 dark:bg-green-900/40 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4 transition-opacity duration-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div id="alert-error"
                    class="bg-red-100 dark:bg-red-900/40 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-4 transition-opacity duration-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500 transition-colors duration-300">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalTransaksi }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500 transition-colors duration-300">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sedang Dipinjam</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalDipinjam }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500 transition-colors duration-300">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terlambat</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalTerlambat }}</p>
                </div>
            </div>

            {{-- Filter Pencarian (Multi Criteria) --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 transition-colors duration-300">
                <form action="{{ route('transaksi.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">

                        {{-- Baris 1 --}}
                        <div class="lg:col-span-2">
                            <input type="text" name="keyword"
                                placeholder="Cari Kode TRX, Nama Anggota, Judul Buku..."
                                value="{{ request('keyword') }}"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm w-full focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:border-blue-500 transition-colors duration-300">
                        </div>

                        <div>
                            <select name="status"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:border-blue-500 transition-colors duration-300">
                                <option value="">Semua Status</option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>
                                    Dipinjam</option>
                                <option value="Dikembalikan"
                                    {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>
                                    Terlambat</option>
                            </select>
                        </div>

                        <div>
                            <select name="anggota_id"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:border-blue-500 transition-colors duration-300">
                                <option value="">Semua Anggota</option>
                                @foreach ($anggotasList as $anggota)
                                    <option value="{{ $anggota->id }}"
                                        {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Baris 2 --}}
                        <div class="lg:col-span-2 flex items-center gap-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400 w-24">Tgl Pinjam:</span>
                            <input type="date" name="tanggal_pinjam_start" title="Dari Tanggal"
                                value="{{ request('tanggal_pinjam_start') }}"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:border-blue-500 transition-colors duration-300">
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                            <input type="date" name="tanggal_pinjam_end" title="Sampai Tanggal"
                                value="{{ request('tanggal_pinjam_end') }}"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:border-blue-500 transition-colors duration-300">
                        </div>

                        <div class="lg:col-span-2 flex gap-2 justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700 transition w-full sm:w-auto">Terapkan
                                Filter</button>
                            <a href="{{ route('transaksi.index') }}"
                                class="bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-6 py-2 rounded text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center w-full sm:w-auto">Reset</a>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Tabel Transaksi --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Transaksi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Anggota</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Buku</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Waktu Pinjam</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status & Denda</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($transaksis as $trx)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $trx->kode_transaksi }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $trx->tanggal_pinjam->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $trx->anggota->nama ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $trx->anggota->telepon ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 line-clamp-1 max-w-[200px]"
                                            title="{{ $trx->buku->judul ?? '-' }}">
                                            {{ $trx->buku->judul ?? 'Buku Dihapus' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">Batas:
                                            {{ $trx->tanggal_kembali->format('d M Y') }}</div>
                                        @if ($trx->status === 'Dikembalikan' && $trx->tanggal_dikembalikan)
                                            <div class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">
                                                Kembali:
                                                {{ \Carbon\Carbon::parse($trx->tanggal_dikembalikan)->format('d M Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($trx->status === 'Dipinjam')
                                            @if ($trx->terlambat > 0)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300">Terlambat
                                                    {{ $trx->terlambat }} Hari</span>
                                                <div class="text-xs text-red-600 dark:text-red-400 font-bold mt-1">
                                                    Denda: Rp
                                                    {{ number_format($trx->estimasi_denda, 0, ',', '.') }}</div>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300">Sedang
                                                    Dipinjam</span>
                                            @endif
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300">Dikembalikan</span>
                                            @if ($trx->denda > 0)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Denda
                                                    Dibayar: Rp
                                                    {{ number_format($trx->denda, 0, ',', '.') }}</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            @if ($trx->status === 'Dipinjam')
                                                <form action="{{ route('transaksi.kembalikan', $trx->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="button"
                                                        class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded text-xs transition btn-kembalikan"
                                                        data-kode="{{ $trx->kode_transaksi }}">Kembalikan</button>
                                                </form>
                                            @endif

                                            <a href="{{ route('transaksi.show', $trx->id) }}"
                                                class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-xs transition">Detail</a>

                                            <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-xs transition btn-delete"
                                                    data-kode="{{ $trx->kode_transaksi }}">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Tidak ada transaksi yang ditemukan atau sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if (method_exists($transaksis, 'links') && $transaksis->hasPages())
                    <div
                        class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 sm:px-6">
                        {{ $transaksis->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Flash message auto-hide
                function autoHideAlert(id, delay = 5000) {
                    const el = document.getElementById(id);
                    if (el) {
                        setTimeout(() => {
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 700);
                        }, delay);
                    }
                }
                autoHideAlert('alert-success');
                autoHideAlert('alert-error');

                // Konfirmasi Hapus Data
                document.querySelectorAll('.btn-delete').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const kode = this.dataset.kode;
                        const form = this.closest('form');
                        Swal.fire({
                            title: 'Hapus Transaksi?',
                            text: `Data Transaksi ${kode} akan dihapus permanen!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });

                // Konfirmasi Kembalikan Buku
                document.querySelectorAll('.btn-kembalikan').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const kode = this.dataset.kode;
                        const form = this.closest('form');
                        Swal.fire({
                            title: 'Kembalikan Buku?',
                            text: `Selesaikan peminjaman untuk transaksi ${kode}?`,
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981', // warna hijau (emerald)
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Kembalikan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
