<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Pencarian: "{{ $keyword }}"
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                {{-- Navigasi Tab --}}
                <div class="border-b border-gray-200 bg-gray-50">
                    <nav class="-mb-px flex sm:flex-row flex-col" aria-label="Tabs" id="search-tabs">
                        <button onclick="switchTab('buku')" id="btn-buku" 
                            class="tab-btn w-full sm:w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm border-blue-500 text-blue-600 transition">
                            Buku ({{ $results['buku']->count() }})
                        </button>
                        <button onclick="switchTab('anggota')" id="btn-anggota" 
                            class="tab-btn w-full sm:w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">
                            Anggota ({{ $results['anggota']->count() }})
                        </button>
                        <button onclick="switchTab('transaksi')" id="btn-transaksi" 
                            class="tab-btn w-full sm:w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">
                            Transaksi ({{ $results['transaksi']->count() }})
                        </button>
                    </nav>
                </div>

                {{-- Konten Tab --}}
                <div class="p-6">
                    
                    {{-- Tab Buku --}}
                    <div id="tab-buku" class="tab-content block">
                        @forelse($results['buku'] as $buku)
                            {{-- DIUBAH MENJADI LINK (a tag) --}}
                            <a href="{{ route('buku.show', $buku->id) }}" class="block bg-white border rounded-lg p-4 mb-3 hover:bg-blue-50 hover:border-blue-300 transition shadow-sm group">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h6 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-blue-700 transition">
                                            {!! str_ireplace($keyword, '<mark class="bg-yellow-200 px-1 rounded">'.$keyword.'</mark>', e($buku->judul)) !!}
                                        </h6>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium text-gray-700">{{ $buku->pengarang ?? $buku->penulis }}</span> &mdash; Stok: {{ $buku->stok }}
                                        </p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada buku yang cocok.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tab Anggota --}}
                    <div id="tab-anggota" class="tab-content hidden">
                        @forelse($results['anggota'] as $anggota)
                            {{-- DIUBAH MENJADI LINK (a tag) --}}
                            <a href="{{ route('anggota.show', $anggota->id) }}" class="block bg-white border rounded-lg p-4 mb-3 hover:bg-blue-50 hover:border-blue-300 transition shadow-sm group">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h6 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-blue-700 transition">
                                            {!! str_ireplace($keyword, '<mark class="bg-yellow-200 px-1 rounded">'.$keyword.'</mark>', e($anggota->nama)) !!}
                                        </h6>
                                        <p class="text-sm text-gray-500">
                                            {{ $anggota->email }}
                                        </p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada anggota yang cocok.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tab Transaksi --}}
                    <div id="tab-transaksi" class="tab-content hidden">
                        @forelse($results['transaksi'] as $trx)
                            {{-- DIUBAH MENJADI LINK (a tag) --}}
                            <a href="{{ route('transaksi.show', $trx->id) }}" class="block bg-white border rounded-lg p-4 mb-3 hover:bg-blue-50 hover:border-blue-300 transition shadow-sm group">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h6 class="text-lg font-mono font-semibold text-blue-600 mb-1">
                                            {{ $trx->kode_transaksi }}
                                        </h6>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium text-gray-700">{{ $trx->anggota->nama }}</span> &mdash; {{ $trx->buku->judul }}
                                        </p>
                                    </div>
                                    <div class="text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada transaksi yang cocok.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function switchTab(tabName) {
                // Sembunyikan semua konten tab
                document.querySelectorAll('.tab-content').forEach(el => {
                    el.classList.remove('block');
                    el.classList.add('hidden');
                });
                
                // Reset styling semua tombol tab
                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('border-blue-500', 'text-blue-600');
                    el.classList.add('border-transparent', 'text-gray-500');
                });

                // Tampilkan konten tab yang dipilih
                const targetContent = document.getElementById('tab-' + tabName);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                    targetContent.classList.add('block');
                }

                // Beri highlight pada tombol tab yang aktif
                const activeBtn = document.getElementById('btn-' + tabName);
                if (activeBtn) {
                    activeBtn.classList.remove('border-transparent', 'text-gray-500');
                    activeBtn.classList.add('border-blue-500', 'text-blue-600');
                }
            }
        </script>
    @endpush
</x-app-layout>