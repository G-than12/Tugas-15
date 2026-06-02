<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function search(Request $request)
    {
        $query = Buku::query();

        // 1. Filter keyword (judul, pengarang, penerbit)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul',     'like', "%{$keyword}%")
                    ->orWhere('pengarang', 'like', "%{$keyword}%")
                    ->orWhere('penerbit',  'like', "%{$keyword}%");
            });
        }

        // 2. Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter tahun terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // 4. Filter ketersediaan
        if ($request->filled('ketersediaan')) {
            if ($request->ketersediaan === 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->ketersediaan === 'habis') {
                $query->where('stok', 0);
            }
        }

        $bukus = $query->latest()->get();

        // Statistik hasil filter
        $totalBuku    = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis    = $bukus->where('stok', 0)->count();

        // Ambil daftar kategori & tahun untuk dropdown
        $kategoriList = Buku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $tahunList    = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategoriList',
            'tahunList'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();

        // Statistik untuk card
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();
        $kategoriList = Buku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $tahunList    = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        // Return view dengan data
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategoriList',
            'tahunList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Akan diimplementasi di pertemuan 12
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Akan diimplementasi di pertemuan 12
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find buku by ID, throw 404 if not found
        $buku = Buku::findOrFail($id);

        // Return view detail buku
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Akan diimplementasi di pertemuan 12
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Akan diimplementasi di pertemuan 12
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Akan diimplementasi di pertemuan 12
    }

    /**
     * Filter buku berdasarkan kategori.
     */
    public function filterKategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->latest()->get();

        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori'
        ));
    }
}
