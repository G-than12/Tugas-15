@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Judul Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-speedometer2 text-primary"></i> Dashboard</h1>
        <span class="text-muted"><i class="bi bi-calendar3"></i> {{ now()->format('d F Y') }}</span>
    </div>

    {{-- ===== STATISTIK BUKU ===== --}}
    <h5 class="text-muted mb-3"><i class="bi bi-book"></i> Statistik Buku</h5>
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small mb-1">Total Buku</div>
                        <h2 class="fw-bold mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <i class="bi bi-book-fill" style="font-size:3rem; opacity:0.6"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small mb-1">Buku Tersedia</div>
                        <h2 class="fw-bold mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <i class="bi bi-check-circle-fill" style="font-size:3rem; opacity:0.6"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small mb-1">Buku Habis</div>
                        <h2 class="fw-bold mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <i class="bi bi-x-circle-fill" style="font-size:3rem; opacity:0.6"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STATISTIK ANGGOTA ===== --}}
    <h5 class="text-muted mb-3"><i class="bi bi-people"></i> Statistik Anggota</h5>
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small mb-1">Total Anggota</div>
                        <h2 class="fw-bold mb-0">{{ $totalAnggota }}</h2>
                    </div>
                    <i class="bi bi-people-fill" style="font-size:3rem; opacity:0.6"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small mb-1">Anggota Aktif</div>
                        <h2 class="fw-bold mb-0">{{ $anggotaAktif }}</h2>
                    </div>
                    <i class="bi bi-person-check-fill" style="font-size:3rem; opacity:0.6"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm bg-secondary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small mb-1">Anggota Nonaktif</div>
                        <h2 class="fw-bold mb-0">{{ $anggotaNonaktif }}</h2>
                    </div>
                    <i class="bi bi-person-x-fill" style="font-size:3rem; opacity:0.6"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 5 BUKU TERBARU & 5 ANGGOTA TERBARU ===== --}}
    <div class="row mb-4">
        {{-- Buku Terbaru --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-clock-history"></i> 5 Buku Terbaru
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukuTerbaru as $buku)
                                <tr>
                                    <td>
                                        <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none">
                                            {{ Str::limit($buku->judul, 30) }}
                                        </a>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $buku->kategori }}</span></td>
                                    <td>
                                        @if ($buku->stok > 0)
                                            <span class="badge bg-success">{{ $buku->stok }}</span>
                                        @else
                                            <span class="badge bg-danger">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada buku</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-primary">
                        Lihat Semua Buku <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Anggota Terbaru --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-clock-history"></i> 5 Anggota Terbaru
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggotaTerbaru as $anggota)
                                <tr>
                                    <td>
                                        <a href="{{ route('anggota.show', $anggota->id) }}" class="text-decoration-none">
                                            {{ $anggota->nama }}
                                        </a>
                                    </td>
                                    <td class="small text-muted">{{ Str::limit($anggota->email, 25) }}</td>
                                    <td>
                                        @if ($anggota->status == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada anggota</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-success">
                        Lihat Semua Anggota <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== QUICK LINKS ===== --}}
    <h5 class="text-muted mb-3"><i class="bi bi-grid-3x3-gap"></i> Menu Utama</h5>
    <div class="row">
        <div class="col-md-3 mb-3">
            <a href="{{ route('buku.index') }}" class="text-decoration-none">
                <div class="card text-center shadow-sm py-3 h-100">
                    <i class="bi bi-book-fill text-primary" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold">Daftar Buku</div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('anggota.index') }}" class="text-decoration-none">
                <div class="card text-center shadow-sm py-3 h-100">
                    <i class="bi bi-people-fill text-success" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold">Daftar Anggota</div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('buku.create') }}" class="text-decoration-none">
                <div class="card text-center shadow-sm py-3 h-100">
                    <i class="bi bi-plus-circle-fill text-warning" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold">Tambah Buku</div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('anggota.create') }}" class="text-decoration-none">
                <div class="card text-center shadow-sm py-3 h-100">
                    <i class="bi bi-person-plus-fill text-info" style="font-size:2.5rem"></i>
                    <div class="mt-2 fw-semibold">Tambah Anggota</div>
                </div>
            </a>
        </div>
    </div>

@endsection
