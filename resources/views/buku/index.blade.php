@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="bi bi-book"></i>
            Daftar Buku
        </h1>
        <a href="{{ route('buku.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
    </div>

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Buku</h6>
                            <h2 class="mb-0">{{ $totalBuku }}</h2>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Buku Tersedia</h6>
                            <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Buku Habis</h6>
                            <h2 class="mb-0">{{ $bukuHabis }}</h2>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Kategori --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title">
                <i class="bi bi-funnel"></i> Filter Kategori:
            </h6>
            <div class="btn-group" role="group">
                <a href="{{ route('buku.index') }}"
                    class="btn btn-sm {{ !isset($kategori) ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                <a href="{{ route('buku.kategori', 'Programming') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Programming' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Programming
                </a>
                <a href="{{ route('buku.kategori', 'Database') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Database' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Database
                </a>
                <a href="{{ route('buku.kategori', 'Web Design') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Web Design' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Web Design
                </a>
                <a href="{{ route('buku.kategori', 'Networking') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Networking' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Networking
                </a>
                <a href="{{ route('buku.kategori', 'Data Science') }}"
                    class="btn btn-sm {{ isset($kategori) && $kategori == 'Data Science' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Data Science
                </a>
            </div>
        </div>
    </div>

    {{-- ===== FORM SEARCH & FILTER ADVANCED (TUGAS 3) ===== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-search"></i> Pencarian & Filter Advanced</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('buku.search') }}" method="GET">
                <div class="row g-2">

                    {{-- Input Keyword --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Keyword</label>
                        <input type="text" name="keyword" class="form-control form-control-sm"
                            placeholder="Cari judul, pengarang, penerbit..." value="{{ request('keyword') }}">
                    </div>

                    {{-- Filter Kategori Dropdown --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @isset($kategoriList)
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Filter Tahun Dropdown --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @isset($tahunList)
                                @foreach ($tahunList as $thn)
                                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                                        {{ $thn }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Filter Ketersediaan --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Ketersediaan</label>
                        <select name="ketersediaan" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="habis" {{ request('ketersediaan') == 'habis' ? 'selected' : '' }}>Habis
                            </option>
                        </select>
                    </div>

                    {{-- Tombol Cari & Reset --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
    {{-- ===== AKHIR FORM SEARCH ===== --}}

    {{-- Daftar Buku --}}
    <h5 class="mt-5 mb-3 text-muted"><i class="bi bi-grid-3x3-gap"></i> Tampilan Grid (BukuCard Component)</h5>
    <div class="row">
        @forelse($bukus as $buku)
            <div class="col-md-4 col-lg-3 mb-4">
                <x-buku-card :buku="$buku" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Tidak ada buku untuk ditampilkan.</div>
            </div>
        @endforelse
    </div>

    @if ($bukus->count() > 0)
        <div class="text-center mt-4">
            <p class="text-muted">
                Menampilkan {{ $bukus->count() }} buku
                @isset($kategori)
                    dari kategori <strong>{{ $kategori }}</strong>
                @endisset
            </p>
        </div>
    @endif
@endsection
