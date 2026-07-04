<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\SearchController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes (butuh login)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'exportPdf'])->name('dashboard.export');
    Route::get('/dashboard/data', [\App\Http\Controllers\DashboardController::class, 'data'])
        ->name('dashboard.data');

    // Profile (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== BUKU =====
    // Custom routes HARUS di atas resource
    Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
    Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
    Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])->name('buku.kategori');
    Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulk-delete');
    Route::get('/buku/preview-kode', [BukuController::class, 'previewKodeBuku'])->name('buku.preview-kode');
    Route::resource('buku', BukuController::class);

    // ===== ANGGOTA =====
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('/anggota/search', [AnggotaController::class, 'search'])->name('anggota.search');
    Route::resource('anggota', AnggotaController::class);

    // ===== TRANSAKSI (baru dari modul 14) =====
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::get('/transaksi/laporan/export', [TransaksiController::class, 'laporanExport'])->name('transaksi.laporan.export');
    Route::resource('transaksi', TransaksiController::class);
    Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');


    // search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

require __DIR__ . '/auth.php';
