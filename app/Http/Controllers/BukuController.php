<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBukuRequest;
use App\Http\Requests\StoreBukuRequest;
use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Validation\ValidationException;


class BukuController extends Controller
{
    /**
     * Jumlah buku yang ditampilkan per halaman (grid).
     */
    private const PER_PAGE = 8;

    /**
     * Endpoint AJAX: kembalikan preview kode buku berikutnya untuk kategori tertentu.
     * Dipanggil dari form create saat kategori berubah.
     */
    public function previewKodeBuku(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:Programming,Database,Web Design,Networking,Data Science',
        ]);

        $prefixKategori = [
            'Programming'  => 'PROG',
            'Database'     => 'DB',
            'Web Design'   => 'WEB',
            'Networking'   => 'NET',
            'Data Science' => 'DS',
        ];

        $prefix = $prefixKategori[$request->kategori];

        $nomorTerakhir = Buku::where('kategori', $request->kategori)
            ->where('kode_buku', 'like', "BK-{$prefix}-%")
            ->pluck('kode_buku')
            ->map(function ($kode) {
                return (int) substr($kode, strrpos($kode, '-') + 1);
            })
            ->max();

        $nomorBaru = ($nomorTerakhir ?? 0) + 1;

        return response()->json([
            'kode_buku' => sprintf('BK-%s-%03d', $prefix, $nomorBaru),
        ]);
    }

    /**
     * Pencarian & filter buku (multi-kriteria), dengan pagination.
     */
    public function search(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        // PENTING: hitung statistik SEBELUM paginate(), memakai clone query,
        // supaya angkanya mencerminkan SELURUH hasil filter (bukan cuma 1 halaman).
        $totalBuku    = (clone $query)->count();
        $bukuTersedia = (clone $query)->where('stok', '>', 0)->count();
        $bukuHabis    = (clone $query)->where('stok', '<=', 0)->count();

        $bukus = $query->latest()
            ->paginate(self::PER_PAGE)
            ->appends($request->query());

        // Ambil daftar kategori & tahun untuk dropdown
        $kategoriList = $this->getKategoriList();
        $tahunList    = $this->getTahunList();

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
        // Ambil data buku dengan pagination (8 buku per halaman)
        $bukus = Buku::latest()->paginate(self::PER_PAGE);

        // Statistik untuk card (global, seluruh data)
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', '<=', 0)->count();
        $kategoriList = $this->getKategoriList();
        $tahunList    = $this->getTahunList();

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
        // diimplementasi di pertemuan 12
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        try {
            // Create buku baru dengan validated data
            Buku::create($request->validated());

            // Redirect dengan success message
            return redirect()->route('buku.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
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
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
            $buku = Buku::findOrFail($id);

            // Update buku dengan validated data
            $buku->update($request->validated());

            // Redirect dengan success message
            return redirect()->route('buku.show', $buku->id)
                ->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $judulBuku = $buku->judul;

            // Delete buku
            $buku->delete();

            // Redirect dengan success message
            return redirect()->route('buku.index')
                ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Filter buku berdasarkan kategori (route khusus, misal /buku/kategori/Programming).
     */
    public function filterKategori($kategori)
    {
        $query = Buku::where('kategori', $kategori);

        // Statistik dihitung dari clone query sebelum paginate, supaya akurat
        // untuk SELURUH buku di kategori ini, bukan cuma 1 halaman.
        $totalBuku    = (clone $query)->count();
        $bukuTersedia = (clone $query)->where('stok', '>', 0)->count();
        $bukuHabis    = (clone $query)->where('stok', '<=', 0)->count();

        $bukus = $query->latest()
            ->paginate(self::PER_PAGE)
            ->appends(request()->query());

        $kategoriList = $this->getKategoriList();
        $tahunList    = $this->getTahunList();

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori',
            'kategoriList',
            'tahunList'
        ));
    }

    /**
     * Hapus beberapa buku sekaligus (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'buku_ids'   => 'required|array|min:1',
                'buku_ids.*' => 'integer',
            ], [
                'buku_ids.required' => 'Pilih minimal 1 buku yang ingin dihapus.',
                'buku_ids.min'      => 'Pilih minimal 1 buku.',
            ]);

            $ids    = $request->buku_ids;
            $jumlah = Buku::whereIn('id', $ids)->count();

            Buku::whereIn('id', $ids)->delete();

            return redirect()->route('buku.index')
                ->with('success', "{$jumlah} buku berhasil dihapus!");
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('error', 'Pilih minimal 1 buku yang ingin dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('buku.index')
                ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Export seluruh data buku ke file CSV
     */
    public function export()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::all();

        // Nama file dengan timestamp supaya tidak bentrok
        $filename = 'data_buku_' . date('Y-m-d_His') . '.csv';

        // Header HTTP agar browser langsung mendownload file
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Callback yang menulis isi CSV baris per baris
        $callback = function () use ($bukus) {
            $file = fopen('php://output', 'w');

            // BOM (Byte Order Mark) agar Excel bisa baca karakter Indonesia dengan benar
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Baris pertama = header kolom
            fputcsv($file, [
                'Kode Buku',
                'Judul',
                'Kategori',
                'Pengarang',
                'Penerbit',
                'Tahun Terbit',
                'ISBN',
                'Bahasa',
                'Harga (Rp)',
                'Stok',
                'Deskripsi',
                'Tanggal Input',
            ]);

            // Baris data
            foreach ($bukus as $buku) {
                fputcsv($file, [
                    $buku->kode_buku,
                    $buku->judul,
                    $buku->kategori,
                    $buku->pengarang,
                    $buku->penerbit,
                    $buku->tahun_terbit,
                    $buku->isbn ?? '-',
                    $buku->bahasa,
                    $buku->harga,
                    $buku->stok,
                    $buku->deskripsi ?? '-',
                    $buku->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        // Kirim response berupa stream (tidak perlu simpan file di server)
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: membangun query filter/pencarian buku berdasarkan request.
     */
    private function buildFilterQuery(Request $request)
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

        // 3. Filter tahun terbit (Disesuaikan dengan name form di view: "tahun_terbit")
        if ($request->filled('tahun_terbit')) {
            $query->where('tahun_terbit', $request->tahun_terbit);
        }

        // 4. Filter ketersediaan
        if ($request->filled('ketersediaan')) {
            if ($request->ketersediaan === 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->ketersediaan === 'habis') {
                $query->where('stok', '<=', 0);
            }
        }

        // 5. Filter harga minimum
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }

        // 6. Filter harga maksimum
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        return $query;
    }

    /**
     * Helper: daftar unik kategori untuk dropdown filter.
     */
    private function getKategoriList()
    {
        return Buku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
    }

    /**
     * Helper: daftar unik tahun terbit untuk dropdown filter.
     */
    private function getTahunList()
    {
        return Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');
    }
}
