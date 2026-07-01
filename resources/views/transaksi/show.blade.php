<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Transaksi
            </h2>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:underline">Home</a> /
                <a href="{{ route('transaksi.index') }}" class="hover:underline">Transaksi</a> /
                {{ $transaksi->kode_transaksi }}
            </nav>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if (session('success'))
                <div id="alert-flash"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm transition-opacity duration-500">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="alert-flash"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm transition-opacity duration-500">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Detail Utama --}}
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6">

                    {{-- ALERT KETERLAMBATAN --}}
                    @if (
                        $transaksi->status === 'Dipinjam' &&
                            \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($transaksi->tanggal_kembali)->startOfDay()))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Peringatan Keterlambatan
                                    </h3>
                                    <div class="mt-1 text-sm text-red-700">
                                        <p>Waktu peminjaman buku ini telah melewati batas jatuh tempo
                                            ({{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d F Y') }}).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- END ALERT KETERLAMBATAN --}}

                    <div class="text-center mb-6">
                        <div class="text-6xl mb-2">
                            {{ $transaksi->status == 'Dipinjam' ? '📖' : '📖' }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $transaksi->kode_transaksi }}</h3>
                        @if ($transaksi->status == 'Dipinjam')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">Dipinjam</span>
                        @else
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">Dikembalikan</span>
                        @endif
                    </div>

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500 w-40">Kode Transaksi</td>
                            <td class="py-2">: <code
                                    class="bg-gray-100 px-1 rounded">{{ $transaksi->kode_transaksi }}</code></td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Anggota</td>
                            <td class="py-2">:
                                <a href="{{ route('anggota.show', $transaksi->anggota_id) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $transaksi->anggota->nama ?? '-' }}
                                </a>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Buku</td>
                            <td class="py-2">:
                                <a href="{{ route('buku.show', $transaksi->buku_id) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $transaksi->buku->judul ?? '-' }}
                                </a>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Tanggal Pinjam</td>
                            <td class="py-2">:
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d F Y') }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Jatuh Tempo</td>
                            <td class="py-2">:
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali)->format('d F Y') }}</td>
                        </tr>
                        @if ($transaksi->tanggal_dikembalikan)
                            <tr class="border-b">
                                <td class="py-2 font-medium text-gray-500">Tanggal Dikembalikan</td>
                                <td class="py-2">:
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_dikembalikan)->format('d F Y') }}</td>
                            </tr>
                        @endif
                        @if ($transaksi->denda > 0)
                            <tr class="border-b">
                                <td class="py-2 font-medium text-gray-500">Denda</td>
                                <td class="py-2 text-red-600 font-semibold">: Rp
                                    {{ number_format($transaksi->denda, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($transaksi->keterangan)
                            <tr>
                                <td class="py-2 font-medium text-gray-500">Keterangan</td>
                                <td class="py-2">: {{ $transaksi->keterangan }}</td>
                            </tr>
                        @endif
                    </table>

                    <div class="flex justify-between text-xs text-gray-400 mt-4 pt-4 border-t">
                        <span>Ditambahkan: {{ $transaksi->created_at->format('d M Y H:i') }}</span>
                        <span>Terakhir Update: {{ $transaksi->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                {{-- Sidebar Aksi --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h6 class="font-semibold text-gray-700 mb-3">Aksi</h6>
                        <div class="flex flex-col gap-2">
                            @if ($transaksi->status === 'Dipinjam')
                                <button type="button" id="btn-kembalikan"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">
                                    Tandai Dikembalikan
                                </button>
                                <form action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST"
                                    id="form-kembalikan">
                                    @csrf
                                </form>
                            @endif

                            <a href="{{ route('transaksi.index') }}"
                                class="border border-green-500 text-green-600 text-center px-4 py-2 rounded text-sm hover:bg-green-50 transition">
                                ← Kembali
                            </a>
                        </div>
                    </div>
                </div>

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
