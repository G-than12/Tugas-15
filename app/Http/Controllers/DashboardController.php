<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;

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

        return view('dashboard.index', compact(
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'bukuTerbaru',
            'anggotaTerbaru'
        ));
    }
}
