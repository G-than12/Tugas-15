<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                Daftar Anggota
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('anggota.export') }}"
                    class="bg-green-600 hover:bg-green-700 transition text-white px-4 py-2 rounded text-sm">
                    Export CSV
                </a>
                <a href="{{ route('anggota.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded text-sm">
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
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Anggota</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $totalAnggota }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500 transition-colors duration-300">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Anggota Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $anggotaAktif }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-gray-400 transition-colors duration-300">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Anggota Nonaktif</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $anggotaNonaktif }}</p>
                </div>
            </div>

            {{-- Search & Filter --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 transition-colors duration-300">
                <form action="{{ route('anggota.search') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">

                        {{-- Baris 1 --}}
                        <div class="lg:col-span-2">
                            <input type="text" name="keyword" placeholder="Cari nama / email / telepon"
                                value="{{ request('keyword') }}"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm w-full transition-colors duration-300">
                        </div>
                        <div>
                            <select name="jenis_kelamin"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full transition-colors duration-300">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="Laki-laki"
                                    {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan"
                                    {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <select name="status"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full transition-colors duration-300">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                        {{-- Baris 2 --}}
                        <div>
                            <select name="pekerjaan"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm w-full transition-colors duration-300">
                                <option value="">Semua Pekerjaan</option>
                                @isset($pekerjaanList)
                                    @foreach ($pekerjaanList as $kerja)
                                        <option value="{{ $kerja }}"
                                            {{ request('pekerjaan') == $kerja ? 'selected' : '' }}>
                                            {{ $kerja }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div>
                            <input type="number" name="umur_min" placeholder="Umur Min (Tahun)" min="0"
                                value="{{ request('umur_min') }}"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm w-full transition-colors duration-300">
                        </div>
                        <div>
                            <input type="number" name="umur_max" placeholder="Umur Max (Tahun)" min="0"
                                value="{{ request('umur_max') }}"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm w-full transition-colors duration-300">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm w-full hover:bg-blue-700 transition">Cari</button>
                            <a href="{{ route('anggota.index') }}"
                                class="border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 px-4 py-2 rounded text-sm w-full text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center justify-center">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Anggota
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Kontak
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Pekerjaan
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Umur
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($anggotas as $anggota)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold">
                                                    {{ substr($anggota->nama, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $anggota->nama }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $anggota->kode_anggota }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $anggota->email }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $anggota->telepon }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $anggota->pekerjaan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if ($anggota->tanggal_lahir)
                                            {{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->age }} thn
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $anggota->status == 'Aktif' ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300' }}">
                                            {{ $anggota->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <a href="{{ route('anggota.show', $anggota->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition text-xs font-medium">
                                                Detail
                                            </a>
                                            <a href="{{ route('anggota.edit', $anggota->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 rounded bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition text-xs font-medium">
                                                Edit
                                            </a>
                                            <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn-delete inline-flex items-center px-3 py-1.5 rounded bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/60 transition text-xs font-medium"
                                                    data-nama="{{ $anggota->nama }}">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data anggota.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($anggotas->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $anggotas->appends(request()->query())->links() }}
                    </div>
                @endif
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
