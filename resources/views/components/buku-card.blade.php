<div class="bg-white rounded-lg shadow h-full flex flex-col">
    <div class="bg-gray-50 text-center py-6">
        <svg class="w-12 h-12 text-blue-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
        @php
            $warna = match ($buku->kategori) {
                'Programming' => 'bg-blue-100 text-blue-700',
                'Database' => 'bg-green-100 text-green-700',
                'Web Design' => 'bg-cyan-100 text-cyan-700',
                'Networking' => 'bg-yellow-100 text-yellow-700',
                'Data Science' => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            };
        @endphp
        <span class="inline-block mt-2 text-xs px-2 py-1 rounded-full {{ $warna }}">{{ $buku->kategori }}</span>
    </div>

    <div class="p-4 flex flex-col flex-1">
        <h6 class="font-semibold text-sm text-gray-800 mb-1">
            <a href="{{ route('buku.show', $buku->id) }}" class="hover:text-blue-600">
                {{ Str::limit($buku->judul, 50) }}
            </a>
        </h6>
        <p class="text-xs text-gray-400 mb-1">{{ $buku->pengarang }}</p>
        <p class="text-sm font-bold text-green-600 mb-2">{{ $buku->harga_format }}</p>

        <div class="mb-3">
            @if ($buku->stok > 0)
                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Tersedia
                    ({{ $buku->stok }})</span>
            @else
                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Habis</span>
            @endif
        </div>

        @if ($showActions)
            <div class="mt-auto flex flex-col gap-2">
                <a href="{{ route('buku.show', $buku->id) }}"
                    class="border border-blue-500 text-blue-500 text-center py-1.5 rounded text-xs hover:bg-blue-50">Detail</a>
                <a href="{{ route('buku.edit', $buku->id) }}"
                    class="border border-yellow-500 text-yellow-500 text-center py-1.5 rounded text-xs hover:bg-yellow-50">Edit</a>
                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="delete-form">
                    @csrf @method('DELETE')
                    <button type="button" class="w-full bg-red-500 text-white py-1.5 rounded text-xs btn-delete"
                        data-judul="{{ $buku->judul }}">Hapus</button>
                </form>
            </div>
        @endif
    </div>
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
