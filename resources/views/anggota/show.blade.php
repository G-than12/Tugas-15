<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Anggota
            </h2>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:underline">Home</a> /
                <a href="{{ route('anggota.index') }}" class="hover:underline">Anggota</a> /
                {{ $anggota->nama }}
            </nav>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Detail Utama --}}
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-6">
                        <div class="text-6xl mb-2">
                            {{ $anggota->jenis_kelamin == 'Laki-laki' ? '👨' : '👩' }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $anggota->nama }}</h3>
                        @if ($anggota->status == 'Aktif')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">Anggota
                                Aktif</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">Nonaktif</span>
                        @endif
                    </div>

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500 w-40">Kode Anggota</td>
                            <td class="py-2">: <code
                                    class="bg-gray-100 px-1 rounded">{{ $anggota->kode_anggota }}</code></td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Email</td>
                            <td class="py-2">: {{ $anggota->email }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Telepon</td>
                            <td class="py-2">: {{ $anggota->telepon }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Alamat</td>
                            <td class="py-2">: {{ $anggota->alamat }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Tanggal Lahir</td>
                            <td class="py-2">: {{ $anggota->tanggal_lahir->format('d F Y') }} ({{ $anggota->umur }}
                                tahun)</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Jenis Kelamin</td>
                            <td class="py-2">: {{ $anggota->jenis_kelamin }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium text-gray-500">Pekerjaan</td>
                            <td class="py-2">: {{ $anggota->pekerjaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 font-medium text-gray-500">Tanggal Daftar</td>
                            <td class="py-2">: {{ $anggota->tanggal_daftar->format('d F Y') }}
                                ({{ $anggota->lama_anggota }} hari)</td>
                        </tr>
                    </table>

                    <div class="flex justify-between text-xs text-gray-400 mt-4 pt-4 border-t">
                        <span>Ditambahkan: {{ $anggota->created_at->format('d M Y H:i') }}</span>
                        <span>Terakhir Update: {{ $anggota->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                {{-- Sidebar Aksi --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h6 class="font-semibold text-gray-700 mb-3">Aksi</h6>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('anggota.edit', $anggota->id) }}"
                                class="bg-yellow-400 text-white text-center px-4 py-2 rounded text-sm">
                                Edit Anggota
                            </a>
                            <a href="{{ route('anggota.index') }}"
                                class="border border-green-500 text-green-600 text-center px-4 py-2 rounded text-sm">
                                ← Kembali
                            </a>
                            <hr>
                            <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST"
                                id="form-delete-anggota">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="btn-delete-anggota"
                                    class="w-full bg-red-600 text-white px-4 py-2 rounded text-sm">
                                    Hapus Anggota
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

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
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-delete-anggota').submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
