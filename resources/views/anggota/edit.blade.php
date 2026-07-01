<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Anggota: {{ $anggota->nama }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                <form action="{{ route('anggota.update', $anggota->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        {{-- Kode Anggota --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Anggota <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="kode_anggota"
                                class="w-full border rounded px-3 py-2 text-sm @error('kode_anggota') border-red-500 @enderror"
                                value="{{ old('kode_anggota', $anggota->kode_anggota) }}">
                            @error('kode_anggota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama"
                                class="w-full border rounded px-3 py-2 text-sm @error('nama') border-red-500 @enderror"
                                value="{{ old('nama', $anggota->nama) }}">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email"
                                class="w-full border rounded px-3 py-2 text-sm @error('email') border-red-500 @enderror"
                                value="{{ old('email', $anggota->email) }}">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Telepon --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telepon" id="telepon"
                                class="w-full border rounded px-3 py-2 text-sm @error('telepon') border-red-500 @enderror"
                                value="{{ old('telepon', $anggota->telepon) }}">
                            @error('telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" rows="3"
                            class="w-full border rounded px-3 py-2 text-sm @error('alamat') border-red-500 @enderror">{{ old('alamat', $anggota->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir"
                                class="w-full border rounded px-3 py-2 text-sm @error('tanggal_lahir') border-red-500 @enderror"
                                value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}">
                            @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin"
                                class="w-full border rounded px-3 py-2 text-sm @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="">-- Pilih --</option>
                                @foreach (['Laki-laki', 'Perempuan'] as $jk)
                                    <option value="{{ $jk }}"
                                        {{ old('jenis_kelamin', $anggota->jenis_kelamin) == $jk ? 'selected' : '' }}>
                                        {{ $jk }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan"
                                class="w-full border rounded px-3 py-2 text-sm @error('pekerjaan') border-red-500 @enderror"
                                value="{{ old('pekerjaan', $anggota->pekerjaan) }}">
                            @error('pekerjaan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        {{-- Tanggal Daftar --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Pendaftaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_daftar"
                                class="w-full border rounded px-3 py-2 text-sm @error('tanggal_daftar') border-red-500 @enderror"
                                value="{{ old('tanggal_daftar', $anggota->tanggal_daftar?->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}">
                            @error('tanggal_daftar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                class="w-full border rounded px-3 py-2 text-sm @error('status') border-red-500 @enderror">
                                @foreach (['Aktif', 'Nonaktif'] as $st)
                                    <option value="{{ $st }}"
                                        {{ old('status', $anggota->status) == $st ? 'selected' : '' }}>
                                        {{ $st }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between border-t pt-4">
                        <a href="{{ route('anggota.show', $anggota->id) }}"
                            class="border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm">
                            ← Kembali
                        </a>
                        <button type="submit" class="bg-yellow-400 text-white px-6 py-2 rounded text-sm">
                            Update Anggota
                        </button>
                    </div>
                </form>

            </div>

            {{-- Info Update --}}
            <div class="bg-white rounded-lg shadow p-4 mt-4 text-sm text-gray-500">
                Terdaftar: {{ $anggota->created_at->format('d M Y H:i') }} ·
                Terakhir update: {{ $anggota->updated_at->format('d M Y H:i') }} ·
                Lama anggota: {{ $anggota->lama_anggota }} hari ({{ round($anggota->lama_anggota / 365, 1) }} tahun)
            </div>
        </div>
    </div>
</x-app-layout>
