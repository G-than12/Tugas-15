<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Buku
            </h2>
            <div class="flex gap-2">
                <button id="btn-bulk-delete" class="hidden bg-red-600 text-white px-4 py-2 rounded text-sm"
                    onclick="konfirmasiBulkDelete()">
                    Hapus Terpilih (<span id="jumlah-terpilih">0</span>)
                </button>
                <a href="{{ route('buku.export') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm">Export
                    CSV</a>
                <a href="{{ route('buku.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">+ Tambah
                    Buku</a>
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

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Total Buku</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalBuku }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Buku Tersedia</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $bukuTersedia }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500">Buku Habis</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $bukuHabis }}</p>
                </div>
            </div>

            {{-- Filter Kategori --}}
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <p class="text-sm font-semibold text-gray-600 mb-2">Filter Kategori:</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('buku.index') }}"
                        class="px-3 py-1 rounded text-sm {{ !isset($kategori) ? 'bg-blue-600 text-white' : 'border border-blue-600 text-blue-600' }}">
                        Semua
                    </a>
                    @foreach (['Programming', 'Database', 'Web Design', 'Networking', 'Data Science'] as $kat)
                        <a href="{{ route('buku.kategori', $kat) }}"
                            class="px-3 py-1 rounded text-sm {{ isset($kategori) && $kategori == $kat ? 'bg-blue-600 text-white' : 'border border-blue-600 text-blue-600' }}">
                            {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Search & Filter --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <p class="text-sm font-semibold text-gray-600 mb-3">Pencarian & Filter</p>
                <form action="{{ route('buku.search') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                        <input type="text" name="keyword" placeholder="Cari judul, pengarang..."
                            value="{{ request('keyword') }}" class="border rounded px-3 py-2 text-sm col-span-2">
                        <select name="kategori" class="border rounded px-3 py-2 text-sm">
                            <option value="">Semua Kategori</option>
                            @isset($kategoriList)
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat }}"
                                        {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <select name="ketersediaan" class="border rounded px-3 py-2 text-sm">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>
                                Tersedia</option>
                            <option value="habis" {{ request('ketersediaan') == 'habis' ? 'selected' : '' }}>Habis
                            </option>
                        </select>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm w-full">Cari</button>
                            <a href="{{ route('buku.index') }}"
                                class="border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm w-full text-center">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Pilih Semua --}}
            <div class="flex justify-between items-center mb-3">
                <p class="text-sm text-gray-500">Tampilan Grid Buku</p>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-500">
                    <input type="checkbox" id="select-all" class="w-4 h-4">
                    Pilih Semua
                </label>
            </div>

            {{-- Form Bulk Delete --}}
            <form id="form-bulk-delete" action="{{ route('buku.bulk-delete') }}" method="POST">
                @csrf
                <div id="hidden-ids"></div>
            </form>

            {{-- Grid Buku --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($bukus as $buku)
                    <div class="relative">
                        <label for="buku-{{ $buku->id }}"
                            class="absolute top-2 left-2 z-10 flex items-center gap-1 bg-white border rounded-full px-2 py-1 text-xs text-gray-500 cursor-pointer shadow">
                            <input class="checkbox-buku w-3 h-3" type="checkbox" data-id="{{ $buku->id }}"
                                id="buku-{{ $buku->id }}">
                            Pilih
                        </label>
                        <x-buku-card :buku="$buku" :showActions="true" />
                    </div>
                @empty
                    <div class="col-span-4">
                        <p class="text-center text-gray-400 py-8">Tidak ada buku untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>

            @if ($bukus->count() > 0)
                <p class="text-center text-sm text-gray-400 mt-6">
                    Menampilkan {{ $bukus->count() }} buku
                    @isset($kategori)
                        dari kategori <strong>{{ $kategori }}</strong>
                    @endisset
                </p>
            @endif

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('select-all');

                selectAll.addEventListener('change', function() {
                    document.querySelectorAll('.checkbox-buku').forEach(cb => cb.checked = this.checked);
                    updateJumlah();
                });

                document.querySelectorAll('.checkbox-buku').forEach(cb => {
                    cb.addEventListener('change', function() {
                        selectAll.checked = document.querySelectorAll('.checkbox-buku:not(:checked)')
                            .length === 0;
                        updateJumlah();
                    });
                });

                function updateJumlah() {
                    const n = document.querySelectorAll('.checkbox-buku:checked').length;
                    document.getElementById('jumlah-terpilih').textContent = n;
                    document.getElementById('btn-bulk-delete').classList.toggle('hidden', n === 0);
                }

                window.konfirmasiBulkDelete = function() {
                    const jumlah = document.querySelectorAll('.checkbox-buku:checked').length;
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: `Hapus ${jumlah} buku sekaligus? Tidak bisa dibatalkan!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            const hiddenIds = document.getElementById('hidden-ids');
                            hiddenIds.innerHTML = '';
                            document.querySelectorAll('.checkbox-buku:checked').forEach(cb => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'buku_ids[]';
                                input.value = cb.dataset.id;
                                hiddenIds.appendChild(input);
                            });
                            document.getElementById('form-bulk-delete').submit();
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
