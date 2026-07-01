<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Buku Baru</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <p class="font-semibold mb-1">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('buku.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Buku <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="kode_buku" id="kode_buku" value="{{ old('kode_buku') }}"
                                placeholder="-- Pilih kategori dahulu --" readonly
                                class="w-full border rounded px-3 py-2 text-sm bg-gray-100 text-gray-500 cursor-not-allowed @error('kode_buku') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">Otomatis dibuat berdasarkan kategori.</p>
                            @error('kode_buku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}"
                                placeholder="Masukkan judul buku"
                                class="w-full border rounded px-3 py-2 text-sm @error('judul') border-red-500 @enderror">
                            @error('judul')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori" id="kategori"
                                class="w-full border rounded px-3 py-2 text-sm @error('kategori') border-red-500 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach (['Programming', 'Database', 'Web Design', 'Networking', 'Data Science'] as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}</option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pengarang <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="pengarang" value="{{ old('pengarang') }}"
                                class="w-full border rounded px-3 py-2 text-sm @error('pengarang') border-red-500 @enderror">
                            @error('pengarang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="penerbit" value="{{ old('penerbit') }}"
                                class="w-full border rounded px-3 py-2 text-sm @error('penerbit') border-red-500 @enderror">
                            @error('penerbit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}"
                                min="1900" max="{{ date('Y') }}"
                                class="w-full border rounded px-3 py-2 text-sm @error('tahun_terbit') border-red-500 @enderror">
                            @error('tahun_terbit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="978-xxx"
                                class="w-full border rounded px-3 py-2 text-sm @error('isbn') border-red-500 @enderror">
                            @error('isbn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa <span
                                    class="text-red-500">*</span></label>
                            <select name="bahasa" id="bahasa"
                                class="w-full border rounded px-3 py-2 text-sm @error('bahasa') border-red-500 @enderror">
                                <option value="Indonesia"
                                    {{ old('bahasa', 'Indonesia') == 'Indonesia' ? 'selected' : '' }}>Indonesia
                                </option>
                                <option value="Inggris" {{ old('bahasa') == 'Inggris' ? 'selected' : '' }}>Inggris
                                </option>
                            </select>
                            @error('bahasa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="bahasa-hint" class="text-xs text-gray-400 mt-1 hidden">
                                Kategori Programming wajib berbahasa Inggris.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="harga" value="{{ old('harga', 0) }}" min="0"
                                step="1000"
                                class="w-full border rounded px-3 py-2 text-sm @error('harga') border-red-500 @enderror">
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0"
                                class="w-full border rounded px-3 py-2 text-sm @error('stok') border-red-500 @enderror">
                            @error('stok')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" placeholder="Deskripsi singkat (opsional)"
                            class="w-full border rounded px-3 py-2 text-sm @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between pt-4 border-t">
                        <a href="{{ route('buku.index') }}"
                            class="border border-gray-400 text-gray-600 px-4 py-2 rounded text-sm">← Kembali</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm">Simpan
                            Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const kategoriEl = document.getElementById('kategori');
            const bahasaEl = document.getElementById('bahasa');
            const bahasaHint = document.getElementById('bahasa-hint');
            const kodeBukuEl = document.getElementById('kode_buku');

            // Prefix kode buku per kategori (harus sama dengan mapping di StoreBukuRequest)
            const prefixKategori = {
                'Programming': 'PROG',
                'Database': 'DB',
                'Web Design': 'WEB',
                'Networking': 'NET',
                'Data Science': 'DS',
            };

            // Mencegah race condition: kalau user ganti kategori dengan cepat,
            // hanya hasil dari request TERAKHIR yang boleh ditampilkan.
            let kodeBukuRequestId = 0;

            async function syncKodeBuku() {
                const kategori = kategoriEl.value;

                if (!kategori || !prefixKategori[kategori]) {
                    kodeBukuEl.value = '';
                    kodeBukuEl.placeholder = '-- Pilih kategori dahulu --';
                    return;
                }

                const requestId = ++kodeBukuRequestId;
                kodeBukuEl.value = '';
                kodeBukuEl.placeholder = 'Memuat kode...';

                try {
                    const response = await fetch(
                        `{{ route('buku.preview-kode') }}?kategori=${encodeURIComponent(kategori)}`, {
                            headers: { 'Accept': 'application/json' },
                        }
                    );

                    if (!response.ok) {
                        throw new Error('Gagal mengambil preview kode buku');
                    }

                    const data = await response.json();

                    // Abaikan response yang sudah usang (user keburu ganti kategori lagi)
                    if (requestId !== kodeBukuRequestId) return;

                    kodeBukuEl.value = data.kode_buku;
                } catch (error) {
                    if (requestId !== kodeBukuRequestId) return;

                    // Fallback: kalau request gagal (mis. koneksi terputus),
                    // tampilkan preview kasar. Nomor final tetap dihitung &
                    // dijamin unik di server saat form disimpan.
                    kodeBukuEl.value = `BK-${prefixKategori[kategori]}-xxx`;
                    kodeBukuEl.placeholder = '-- Pilih kategori dahulu --';
                }
            }

            kategoriEl.addEventListener('change', syncKodeBuku);
            syncKodeBuku(); // jalankan saat halaman load, untuk kasus old('kategori') sudah terisi

            function syncBahasa() {
                if (kategoriEl.value === 'Programming') {
                    bahasaEl.value = 'Inggris';
                    bahasaEl.disabled = true;
                    bahasaHint.classList.remove('hidden');

                    // tetap kirim value walau disabled, pakai hidden input
                    let hidden = document.getElementById('bahasa-hidden');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'bahasa';
                        hidden.id = 'bahasa-hidden';
                        bahasaEl.insertAdjacentElement('afterend', hidden);
                    }
                    hidden.value = 'Inggris';
                } else {
                    bahasaEl.disabled = false;
                    bahasaHint.classList.add('hidden');
                    const hidden = document.getElementById('bahasa-hidden');
                    if (hidden) hidden.remove();
                }
            }

            kategoriEl.addEventListener('change', syncBahasa);
            syncBahasa(); // jalankan saat halaman load, untuk kasus old('kategori') sudah Programming
        </script>
    @endpush
</x-app-layout>
