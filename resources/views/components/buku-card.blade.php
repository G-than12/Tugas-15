<div class="card h-100 shadow-sm">
    {{-- Cover / Icon --}}
    <div class="card-img-top bg-light text-center py-4">
        <i class="bi bi-book-fill text-primary" style="font-size: 4rem;"></i>
        {{-- Badge Kategori --}}
        <div class="mt-2">
            @php
                $warna = match ($buku->kategori) {
                    'Programming' => 'primary',
                    'Database' => 'success',
                    'Web Design' => 'info',
                    'Networking' => 'warning',
                    'Data Science' => 'danger',
                    default => 'secondary',
                };
            @endphp
            <span class="badge bg-{{ $warna }}">{{ $buku->kategori }}</span>
        </div>
    </div>

    <div class="card-body d-flex flex-column">
        {{-- Judul --}}
        <h6 class="card-title fw-bold">
            <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none text-dark">
                {{ Str::limit($buku->judul, 50) }}
            </a>
        </h6>

        {{-- Pengarang --}}
        <p class="card-text text-muted small mb-1">
            <i class="bi bi-person"></i> {{ $buku->pengarang }}
        </p>

        {{-- Harga --}}
        <p class="card-text text-success fw-bold">
            {{ $buku->harga_format }}
        </p>

        {{-- Stok / Status Ketersediaan --}}
        <div class="mb-3">
            @if ($buku->stok > 0)
                <span class="badge bg-success">
                    <i class="bi bi-check-circle"></i> Tersedia ({{ $buku->stok }})
                </span>
            @else
                <span class="badge bg-danger">
                    <i class="bi bi-x-circle"></i> Habis
                </span>
            @endif
        </div>

        {{-- Spacer agar button selalu di bawah --}}
        <div class="mt-auto">
            {{-- Button Actions — hanya tampil jika $showActions = true --}}
            @if ($showActions)
                <div class="d-grid gap-2">
                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
