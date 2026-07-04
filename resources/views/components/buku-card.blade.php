@props(['buku', 'showActions' => false])

<div
    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full overflow-hidden relative">

    {{-- Bagian Atas: Background Warna Lembut & Ikon --}}
    <div
        class="flex justify-center items-center py-6
        @if ($buku->kategori == 'Programming') bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
        @elseif($buku->kategori == 'Database') bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400
        @elseif($buku->kategori == 'Web Design') bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400
        @elseif($buku->kategori == 'Networking') bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400
        @elseif($buku->kategori == 'Data Science') bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400
        @else bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 @endif
    ">
        @switch($buku->kategori)
            @case('Programming')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                </svg>
            @break

            @case('Database')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
            @break

            @case('Web Design')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.879-5.831a1.5 1.5 0 00-1.4-2.27h-3.382a2.75 2.75 0 00-2.75 2.75v3.381a1.5 1.5 0 002.27 1.4l5.83-3.88a15.994 15.994 0 00-4.647 4.764z" />
                </svg>
            @break

            @case('Networking')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>
            @break

            @case('Data Science')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            @break

            @default
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
        @endswitch
    </div>

    {{-- Detail Informasi Buku --}}
    <div class="p-4 flex-grow flex flex-col">
        {{-- Kategori Badge --}}
        <div class="mb-2">
            <span
                class="px-2 py-1 text-xs font-semibold rounded-full
                @if ($buku->kategori == 'Programming') bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300
                @elseif($buku->kategori == 'Database') bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300
                @elseif($buku->kategori == 'Web Design') bg-pink-100 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300
                @elseif($buku->kategori == 'Networking') bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300
                @elseif($buku->kategori == 'Data Science') bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300
                @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 @endif
            ">
                {{ $buku->kategori }}
            </span>
        </div>

        {{-- Judul & Pengarang --}}
        <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 line-clamp-2" title="{{ $buku->judul }}">
            {{ $buku->judul }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $buku->pengarang }}</p>

        <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <span class="font-bold text-gray-800 dark:text-gray-100">Rp
                    {{ number_format($buku->harga, 0, ',', '.') }}</span>
                <span
                    class="text-xs {{ $buku->stok > 0 ? 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/40' : 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/40' }} px-2 py-1 rounded">
                    {{ $buku->stok > 0 ? 'Stok: ' . $buku->stok : 'Habis' }}
                </span>
            </div>
            <div class="text-xs text-gray-400 dark:text-gray-500">
                Terbit: {{ $buku->tahun_terbit }}
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    @if ($showActions)
        <div
            class="bg-gray-50 dark:bg-gray-900/40 p-3 border-t border-gray-200 dark:border-gray-700 flex justify-between gap-2">
            <a href="{{ route('buku.show', $buku->id) }}"
                class="flex-1 text-center bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 py-1.5 rounded text-sm transition">Detail</a>
            <a href="{{ route('buku.edit', $buku->id) }}"
                class="flex-1 text-center bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/60 py-1.5 rounded text-sm transition">Edit</a>

            {{-- Form Hapus dengan class "delete-form" dan button "btn-delete" sesuai kode Anda --}}
            <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="flex-1 m-0 delete-form">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="w-full text-center bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/60 py-1.5 rounded text-sm transition btn-delete"
                    data-judul="{{ $buku->judul }}">
                    Hapus
                </button>
            </form>
        </div>
    @endif
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        const form = this.closest('form');
                        const judul = this.dataset.judul;
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: `Hapus buku "${judul}"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then(result => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
@endonce
