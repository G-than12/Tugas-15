<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Models\Buku;
use App\Models\Anggota;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ========== RESOURCE ROUTES (PRAKTIKUM 2) ==========

// Custom route filter kategori HARUS di atas resource
// agar tidak ditangkap oleh /buku/{buku}
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
    ->name('buku.kategori');

Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');

// Resource route untuk Buku
// Otomatis membuat: buku.index, buku.create, buku.store,
//                   buku.show, buku.edit, buku.update, buku.destroy
Route::resource('buku', BukuController::class);

// Resource route untuk Anggota
// Otomatis membuat: anggota.index, anggota.create, anggota.store,
//                   anggota.show, anggota.edit, anggota.update, anggota.destroy
Route::resource('anggota', AnggotaController::class);

// ========== TESTING SCOPE & QUERY (PERTEMUAN 10) ==========

Route::get('/test-query', function () {
    $html = '<h1>Testing Query Eloquent</h1>';

    $tersedia = Buku::tersedia()->get();
    $html .= '<h3>Buku Tersedia (Stok > 0): ' . $tersedia->count() . '</h3>';
    $html .= '<ul>';
    foreach ($tersedia as $buku) {
        $html .= '<li>' . $buku->judul . ' (Stok: ' . $buku->stok . ')</li>';
    }
    $html .= '</ul>';

    $programming = Buku::kategori('Programming')->get();
    $html .= '<h3>Buku Programming: ' . $programming->count() . '</h3>';
    $html .= '<ul>';
    foreach ($programming as $buku) {
        $html .= '<li>' . $buku->judul . '</li>';
    }
    $html .= '</ul>';

    $aktif = Anggota::aktif()->get();
    $html .= '<h3>Anggota Aktif: ' . $aktif->count() . '</h3>';
    $html .= '<ul>';
    foreach ($aktif as $anggota) {
        $html .= '<li>' . $anggota->nama . ' (' . $anggota->email . ')</li>';
    }
    $html .= '</ul>';

    return $html;
});

// ========== TESTING ACCESSOR & SCOPE (PERTEMUAN 10 - TUGAS 2) ==========

Route::get('/test-accessor-scope', function () {
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Testing Accessor & Scope</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body class="container py-4">';

    $html .= '<h2 class="mt-4">📚 Buku — Status Stok Badge & Tahun Label</h2>';
    $html .= '<table class="table table-bordered">';
    $html .= '<tr><th>Judul</th><th>Stok</th><th>Status Stok</th><th>Tahun Label</th></tr>';
    foreach (Buku::all() as $buku) {
        $html .= '<tr>';
        $html .= '<td>' . $buku->judul . '</td>';
        $html .= '<td>' . $buku->stok . '</td>';
        $html .= '<td>' . $buku->status_stok_badge . '</td>';
        $html .= '<td>' . $buku->tahun_label . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    $html .= '<h2 class="mt-4">🆕 Scope: Buku Terbaru (tahun >= 2024)</h2>';
    $html .= '<ul class="list-group">';
    foreach (Buku::terbaru()->get() as $buku) {
        $html .= '<li class="list-group-item">' . $buku->judul . ' (' . $buku->tahun_terbit . ')</li>';
    }
    $html .= '</ul>';

    $html .= '<h2 class="mt-4">⚠️ Scope: Buku Stok Menipis (stok < 5)</h2>';
    $html .= '<ul class="list-group">';
    foreach (Buku::stokMenipis()->get() as $buku) {
        $html .= '<li class="list-group-item">' . $buku->judul . ' — Stok: ' . $buku->stok . '</li>';
    }
    $html .= '</ul>';

    $html .= '<h2 class="mt-4">💰 Scope: Buku Harga Range Rp100.000 - Rp160.000</h2>';
    $html .= '<ul class="list-group">';
    foreach (Buku::hargaRange(100000, 160000)->get() as $buku) {
        $html .= '<li class="list-group-item">' . $buku->judul . ' — ' . $buku->harga_format . '</li>';
    }
    $html .= '</ul>';

    $html .= '<h2 class="mt-5">👥 Anggota — Status Badge & Kategori Usia</h2>';
    $html .= '<table class="table table-bordered">';
    $html .= '<tr><th>Nama</th><th>Umur</th><th>Kategori Usia</th><th>Status</th></tr>';
    foreach (Anggota::all() as $anggota) {
        $html .= '<tr>';
        $html .= '<td>' . $anggota->nama . '</td>';
        $html .= '<td>' . $anggota->umur . ' tahun</td>';
        $html .= '<td>' . $anggota->kategori_usia . '</td>';
        $html .= '<td>' . $anggota->status_badge . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    $html .= '<h2 class="mt-4">📅 Scope: Anggota Terdaftar Bulan Ini</h2>';
    $bulanIni = Anggota::terdaftarBulanIni()->get();
    if ($bulanIni->count() > 0) {
        $html .= '<ul class="list-group">';
        foreach ($bulanIni as $anggota) {
            $html .= '<li class="list-group-item">' . $anggota->nama . ' — Daftar: ' . $anggota->tanggal_daftar->format('d-m-Y') . '</li>';
        }
        $html .= '</ul>';
    } else {
        $html .= '<p class="text-muted">Tidak ada anggota yang mendaftar bulan ini.</p>';
    }

    $html .= '</body></html>';

    return $html;
});
