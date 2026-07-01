<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Form Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                {{-- Flash Error --}}
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf

                    {{-- Pilih Anggota --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Anggota <span class="text-red-500">*</span>
                        </label>
                        <select name="anggota_id"
                            class="w-full border rounded px-3 py-2 text-sm @error('anggota_id') border-red-500 @enderror">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach ($anggotas as $anggota)
                                <option value="{{ $anggota->id }}"
                                    {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                    {{ $anggota->kode_anggota }} - {{ $anggota->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">Hanya anggota dengan status Aktif yang dapat meminjam</p>
                    </div>

                    {{-- Pilih Buku --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Buku <span class="text-red-500">*</span>
                        </label>
                        <select name="buku_id"
                            class="w-full border rounded px-3 py-2 text-sm @error('buku_id') border-red-500 @enderror">
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($bukus as $buku)
                                <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                    {{ $buku->judul }} - (Stok: {{ $buku->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">Hanya buku dengan stok tersedia yang dapat dipinjam</p>
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Pinjam <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}"
                            class="w-full border rounded px-3 py-2 text-sm @error('tanggal_pinjam') border-red-500 @enderror">
                        @error('tanggal_pinjam')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">Tanggal kembali otomatis 7 hari dari tanggal pinjam</p>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)"
                            class="w-full border rounded px-3 py-2 text-sm @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded p-4 mb-4 text-sm">
                        <p class="font-semibold mb-2">Informasi Peminjaman:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Durasi peminjaman: <strong>7 hari</strong></li>
                            <li>Denda keterlambatan: <strong>Rp 5.000/hari</strong></li>
                            <li>Stok buku akan berkurang otomatis setelah peminjaman</li>
                        </ul>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-between pt-4 border-t">
                        <a href="{{ route('transaksi.index') }}"
                            class="border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm">
                            ← Kembali
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm">
                            Proses Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
