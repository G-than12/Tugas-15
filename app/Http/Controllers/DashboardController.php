<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // === Statistik Buku ===
        $totalBuku      = Buku::count();
        $bukuTersedia   = Buku::where('stok', '>', 0)->count();
        $bukuHabis      = Buku::where('stok', 0)->count();

        // === Statistik Anggota ===
        $totalAnggota       = Anggota::count();
        $anggotaAktif       = Anggota::where('status', 'Aktif')->count();
        $anggotaNonaktif    = Anggota::where('status', 'Nonaktif')->count();

        // === 5 Buku Terbaru ===
        $bukuTerbaru = Buku::latest()->take(5)->get();

        // === 5 Anggota Terbaru ===
        $anggotaTerbaru = Anggota::latest()->take(5)->get();

        // === Tren Peminjaman 7 Hari Terakhir ===
        $trenPeminjaman = $this->getTrenPeminjaman();

        // === TUGAS 3: DATA BUKU TERLAMBAT ===
        // Menggunakan scopeTerlambat() dari model Transaksi
        $totalTerlambat = Transaksi::terlambat()->count();
        $listTerlambat = Transaksi::with(['anggota', 'buku'])
            ->terlambat()
            ->latest()
            ->take(5) // Ambil 5 data terbaru untuk list di dashboard
            ->get();

        return view('dashboard.index', compact(
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'bukuTerbaru',
            'anggotaTerbaru',
            'trenPeminjaman',
            'totalTerlambat', // Tambahkan ini
            'listTerlambat'   // Tambahkan ini
        ));
    }

    /**
     * Hitung jumlah transaksi peminjaman per hari untuk 7 hari terakhir.
     */
    private function getTrenPeminjaman(): array
    {
        $namaHari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $tren = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i);

            $total = Transaksi::whereDate('tanggal_pinjam', $tanggal->toDateString())->count();

            $tren[] = [
                'label' => $namaHari[$tanggal->dayOfWeek],
                'total' => $total,
            ];
        }

        return $tren;
    }
}
