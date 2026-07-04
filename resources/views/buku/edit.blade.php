<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Edit Buku: {{ $buku->judul }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-300">
                <form action="{{ route('buku.update', $buku->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Buku
                                <span class="text-red-500">*</span></label>
                            <input type="text" value="{{ $buku->kode_buku }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm bg-gray-100 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kode buku tidak dapat diubah
                                setelah buku dibuat.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Buku
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori
                                <span class="text-red-500">*</span></label>
                            <input type="text" value="{{ $buku->kategori }}" readonly
                                class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 text-sm bg-gray-100 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kategori tidak dapat diubah karena
                                menentukan kode
                                buku.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pengarang
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('pengarang') border-red-500 @enderror">
                            @error('pengarang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penerbit
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('penerbit') border-red-500 @enderror">
                            @error('penerbit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_terbit"
                                value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900"
                                max="{{ date('Y') }}"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('tahun_terbit') border-red-500 @enderror">
                            @error('tahun_terbit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bahasa <span
                                    class="text-red-500">*</span></label>
                            <select name="bahasa" id="bahasa"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm">
                                <option value="Indonesia"
                                    {{ old('bahasa', $buku->bahasa) == 'Indonesia' ? 'selected' : '' }}
                                    {{ $buku->kategori === 'Programming' ? 'disabled' : '' }}>Indonesia
                                </option>
                                <option value="Inggris"
                                    {{ old('bahasa', $buku->bahasa) == 'Inggris' ? 'selected' : '' }}>Inggris</option>
                            </select>
                            @if ($buku->kategori === 'Programming')
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kategori Programming wajib
                                    berbahasa Inggris.
                                </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="harga" value="{{ old('harga', $buku->harga) }}"
                                min="0" step="1000"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('harga') border-red-500 @enderror">
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" min="0"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm @error('stok') border-red-500 @enderror">
                            @error('stok')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 text-sm">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('buku.show', $buku->id) }}"
                            class="border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition px-4 py-2 rounded text-sm">←
                            Kembali</a>
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-6 py-2 rounded text-sm">Update
                            Buku</button>
                    </div>
                </form>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mt-4 text-xs text-gray-400 dark:text-gray-500 transition-colors duration-300">
                Ditambahkan: {{ $buku->created_at->format('d M Y H:i') }} &nbsp;|&nbsp;
                Terakhir diupdate: {{ $buku->updated_at->format('d M Y H:i') }}
            </div>
        </div>
    </div>
</x-app-layout>
