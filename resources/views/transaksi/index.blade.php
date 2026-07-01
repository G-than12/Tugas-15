<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Transaksi Peminjaman
            </h2>
            <div class="flex gap-2">
                <form action="{{ route('transaksi.laporan.export') }}" method="GET" id="form-export-header">
                    @foreach (request()->except('page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
                <button type="submit" form="form-export-header"
                    class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 transition">
                    Export PDF
                </button>
                <a href="{{ route('transaksi.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">
                    + Pinjam Buku
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div id="alert-success"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 transition-opacity duration-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div id="alert-error"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 transition-opacity duration-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ method_exists($transaksis, 'total') ? $transaksis->total() : $transaksis->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500">Sedang Dipinjam</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $statDipinjam ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Sudah Dikembalikan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $statDikembalikan ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500">Total Denda</p>
                    <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($statDenda ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('transaksi.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="border rounded px-3 py-2 text-sm w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="border rounded px-3 py-2 text-sm w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status" class="border rounded px-3 py-2 text-sm w-full">
                                <option value="Semua" {{ request('status', 'Semua') == 'Semua' ? 'selected' : '' }}>
                                    Semua</option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>
                                    Dipinjam</option>
                                <option value="Dikembalikan"
                                    {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>
                                    Dikembalikan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Anggota</label>
                            <select name="anggota_id" class="border rounded px-3 py-2 text-sm w-full">
                                <option value="">-- Semua Anggota --</option>
                                @foreach ($anggotas as $anggota)
                                    <option value="{{ $anggota->id }}"
                                        {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm w-full hover:bg-blue-700 transition">
                                Filter
                            </button>
                            <a href="{{ route('transaksi.index') }}"
                                class="border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm w-full text-center hover:bg-gray-100 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode
                                    Transaksi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggota</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transaksis as $transaksi)
                                <tr
                                    class="hover:bg-gray-50 transition-colors
                                    {{-- Highlight baris merah muda jika terlambat --}}
                                    {{ $transaksi->status === 'Dipinjam' && $transaksi->terlambat > 0 ? 'bg-red-50' : '' }}">

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ method_exists($transaksis, 'firstItem') ? $transaksis->firstItem() + $loop->index : $loop->iteration }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <code class="bg-gray-100 px-2 py-1 rounded text-blue-600 font-mono">
                                            {{ $transaksi->kode_transaksi }}
                                        </code>
                                    </td>

                                    <td class="px-4 py-3 font-semibold text-gray-800">
                                        {{ $transaksi->anggota->nama }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-700">
                                        {{ Str::limit($transaksi->buku->judul, 30) }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $transaksi->tanggal_pinjam->format('d M Y') }}
                                    </td>

                                    {{-- Tanggal kembali — merah jika sudah lewat & belum dikembalikan --}}
                                    <td
                                        class="px-4 py-3 font-medium
                                        {{ $transaksi->status === 'Dipinjam' && $transaksi->terlambat > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $transaksi->tanggal_kembali->format('d M Y') }}
                                    </td>

                                    {{-- Kolom STATUS + badge Terlambat --}}
                                    <td class="px-4 py-3">
                                        @if ($transaksi->status === 'Dipinjam')
                                            <span
                                                class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">
                                                Dipinjam
                                            </span>
                                            @if ($transaksi->terlambat > 0)
                                                <span
                                                    class="mt-1 inline-block bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">
                                                    Terlambat {{ $transaksi->terlambat }} hari
                                                </span>
                                            @endif
                                        @else
                                            <span
                                                class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">
                                                Dikembalikan
                                            </span>
                                            @if ($transaksi->terlambat > 0)
                                                <span
                                                    class="mt-1 inline-block bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">
                                                    Telat {{ $transaksi->terlambat }} hari
                                                </span>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Kolom DENDA --}}
                                    <td class="px-4 py-3 font-medium">
                                        @if ($transaksi->status === 'Dikembalikan')
                                            {{-- Denda final dari DB --}}
                                            @if ($transaksi->denda > 0)
                                                <span class="text-red-600">
                                                    Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        @else
                                            {{-- Estimasi denda realtime selama masih dipinjam --}}
                                            @if ($transaksi->estimasi_denda > 0)
                                                <span class="text-orange-500">
                                                    ~Rp {{ number_format($transaksi->estimasi_denda, 0, ',', '.') }}
                                                </span>
                                                <br>
                                                <span class="text-xs text-gray-400">estimasi</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                            class="bg-blue-500 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-600 transition shadow-sm">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
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
                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 sm:px-6">
                        {{ $transaksis->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
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
        </script>
    @endpush
</x-app-layout>
