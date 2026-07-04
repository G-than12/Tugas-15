<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Tambah Anggota Baru
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-300">

                <form action="{{ route('anggota.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        {{-- Kode Anggota --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Kode Anggota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_anggota"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm bg-gray-100 dark:bg-gray-900/50 dark:text-gray-300 @error('kode_anggota') border-red-500 @enderror"
                                value="{{ old('kode_anggota', $kodeAnggota) }}" readonly>
                            @error('kode_anggota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm @error('nama') border-red-500 @enderror"
                                value="{{ old('nama') }}" placeholder="Nama lengkap anggota">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}" placeholder="email@example.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telepon" id="telepon"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm @error('telepon') border-red-500 @enderror"
                                value="{{ old('telepon') }}" placeholder="081234567890">
                            @error('telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Format: 08xxxxxxxxxx atau
                                +628xxxxxxxxxx</p>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" rows="3"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm @error('alamat') border-red-500 @enderror"
                            placeholder="Alamat lengkap dengan kota dan kode pos">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('tanggal_lahir') border-red-500 @enderror"
                                value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}">
                            @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400 rounded px-3 py-2 text-sm @error('pekerjaan') border-red-500 @enderror"
                                value="{{ old('pekerjaan') }}" placeholder="Mahasiswa, Pegawai, dll">
                            @error('pekerjaan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        {{-- Tanggal Daftar --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Pendaftaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_daftar" id="tanggal_daftar"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('tanggal_daftar') border-red-500 @enderror"
                                value="{{ old('tanggal_daftar', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                            @error('tanggal_daftar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('status') border-red-500 @enderror">
                                <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                        <a href="{{ route('anggota.index') }}"
                            class="border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition px-4 py-2 rounded text-sm">
                            ← Kembali
                        </a>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 transition text-white px-6 py-2 rounded text-sm">
                            Simpan Anggota
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('telepon').addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+]/g, '');
            });
        </script>
    @endpush
</x-app-layout>
