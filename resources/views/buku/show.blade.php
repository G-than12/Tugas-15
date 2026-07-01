<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Buku
            </h2>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:underline">Home</a> /
                <a href="{{ route('buku.index') }}" class="hover:underline">Buku</a> /
                {{ Str::limit($buku->judul, 30) }}
            </nav>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Detail Utama --}}
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-6">
                        <div class="text-6xl mb-2">📖</div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $buku->judul }}</h3>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mt-1">
                            {{ $buku->kategori }}
                        </span>
                        @if ($buku->stok > 0)
                            <span
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs ml-1">Tersedia</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs ml-1">Stok Habis</span>
                        @endif
                    </div>

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500 w-40">Kode Buku</td>
                            <td class="py-2">: <code class="bg-gray-100 px-1 rounded">{{ $buku->kode_buku }}</code>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Pengarang</td>
                            <td class="py-2">: {{ $buku->pengarang }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Penerbit</td>
                            <td class="py-2">: {{ $buku->penerbit }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Tahun Terbit</td>
                            <td class="py-2">: {{ $buku->tahun_terbit }}</td>
                        </tr>
                        @if ($buku->isbn)
                            <tr class="border-b">
                                <td class="py-2 font-medium text-gray-500">ISBN</td>
                                <td class="py-2">: {{ $buku->isbn }}</td>
                            </tr>
                        @endif
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Bahasa</td>
                            <td class="py-2">: {{ $buku->bahasa }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Harga</td>
                            <td class="py-2">: <span
                                    class="text-green-600 font-bold">{{ $buku->harga_format }}</span></td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium text-gray-500">Stok</td>
                            <td class="py-2">
                                : {{ $buku->stok }} buku
                                @if ($buku->stok == 0)
                                    <span
                                        class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full ml-1">Habis</span>
                                @elseif($buku->stok <= 5)
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full ml-1">Menipis</span>
                                @else
                                    <span
                                        class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full ml-1">Aman</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if ($buku->deskripsi)
                        <div class="mt-4 pt-4 border-t">
                            <p class="font-medium text-gray-500 text-sm mb-1">Deskripsi</p>
                            <p class="text-sm text-gray-700">{{ $buku->deskripsi }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between text-xs text-gray-400 mt-4 pt-4 border-t">
                        <span>Ditambahkan: {{ $buku->created_at->format('d M Y H:i') }}</span>
                        <span>Terakhir Update: {{ $buku->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-4">
                    {{-- Aksi --}}
                    <div class="bg-white rounded-lg shadow p-4">
                        <h6 class="font-semibold text-gray-700 mb-3">Aksi</h6>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('buku.edit', $buku->id) }}"
                                class="bg-yellow-400 text-white text-center px-4 py-2 rounded text-sm">
                                Edit Buku
                            </a>
                            @if ($buku->stok > 0)
                                <a href="{{ route('transaksi.create') }}"
                                    class="bg-green-600 text-white text-center px-4 py-2 rounded text-sm">
                                    Pinjam Buku
                                </a>
                            @else
                                <button disabled
                                    class="bg-gray-300 text-gray-500 px-4 py-2 rounded text-sm cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                            <a href="{{ route('buku.index') }}"
                                class="border border-green-500 text-green-600 text-center px-4 py-2 rounded text-sm">
                                ← Kembali
                            </a>
                            <hr>
                            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" id="form-delete-buku">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="btn-delete-buku"
                                    class="w-full bg-red-600 text-white px-4 py-2 rounded text-sm">
                                    Hapus Buku
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Status Stok --}}
                    <div class="bg-white rounded-lg shadow p-4">
                        <h6 class="font-semibold text-gray-700 mb-2">Status Stok</h6>
                        @if ($buku->stok == 0)
                            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded p-3">Stok Habis!
                            </div>
                        @elseif($buku->stok <= 5)
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm rounded p-3">
                                Stok Menipis! Tersisa {{ $buku->stok }}
                            </div>
                        @else
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded p-3">
                                Stok Aman! Tersedia {{ $buku->stok }}
                            </div>
                        @endif
                    </div>

                    {{-- Buku Serupa --}}
                    <div class="bg-white rounded-lg shadow p-4">
                        <h6 class="font-semibold text-gray-700 mb-3">Buku Serupa</h6>
                        @php
                            $bukuSerupa = App\Models\Buku::where('kategori', $buku->kategori)
                                ->where('id', '!=', $buku->id)
                                ->take(3)
                                ->get();
                        @endphp
                        @forelse($bukuSerupa as $item)
                            <div class="mb-3 pb-3 border-b last:border-0 last:mb-0 last:pb-0">
                                <a href="{{ route('buku.show', $item->id) }}"
                                    class="text-sm font-medium text-blue-600 hover:underline">
                                    {{ Str::limit($item->judul, 40) }}
                                </a>
                                <p class="text-xs text-gray-400">{{ $item->pengarang }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400">Tidak ada buku serupa</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('btn-delete-buku').addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus Buku?',
                    text: `Buku "{{ $buku->judul }}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-delete-buku').submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
