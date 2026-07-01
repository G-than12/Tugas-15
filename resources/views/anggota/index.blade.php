<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Anggota
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('anggota.export') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm">
                    Export CSV
                </a>
                <a href="{{ route('anggota.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    + Tambah Anggota
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Total Anggota</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalAnggota }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Anggota Aktif</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $anggotaAktif }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-400">
                    <p class="text-sm text-gray-500">Anggota Nonaktif</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $anggotaNonaktif }}</p>
                </div>
            </div>

            {{-- Search & Filter --}}
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('anggota.search') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                        <input type="text" name="keyword" placeholder="Cari nama / email / telepon"
                            value="{{ request('keyword') }}" class="border rounded px-3 py-2 text-sm col-span-2">
                        <select name="jenis_kelamin" class="border rounded px-3 py-2 text-sm">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                        <select name="status" class="border rounded px-3 py-2 text-sm">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm w-full">Cari</button>
                            <a href="{{ route('anggota.index') }}"
                                class="border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm w-full text-center">Reset</a>
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis
                                    Kelamin</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($anggotas as $anggota)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3"><code
                                            class="bg-gray-100 px-1 rounded">{{ $anggota->kode_anggota }}</code></td>
                                    <td class="px-4 py-3 font-semibold">{{ $anggota->nama }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $anggota->email }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $anggota->telepon }}</td>
                                    <td class="px-4 py-3">{{ $anggota->jenis_kelamin }}</td>
                                    <td class="px-4 py-3">
                                        @if ($anggota->status == 'Aktif')
                                            <span
                                                class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Aktif</span>
                                        @else
                                            <span
                                                class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-1">
                                            <a href="{{ route('anggota.show', $anggota->id) }}"
                                                class="bg-blue-500 text-white px-2 py-1 rounded text-xs">Detail</a>
                                            <a href="{{ route('anggota.edit', $anggota->id) }}"
                                                class="bg-yellow-400 text-white px-2 py-1 rounded text-xs">Edit</a>
                                            <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                                                class="form-delete inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="bg-red-600 text-white px-2 py-1 rounded text-xs btn-delete"
                                                    data-nama="{{ $anggota->nama }}">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data
                                        anggota</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const nama = this.dataset.nama;
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Hapus Anggota?',
                        text: `Anggota "${nama}" akan dihapus permanen!`,
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
        </script>
    @endpush
</x-app-layout>
