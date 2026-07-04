<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Transaksi;
use App\Http\Requests\StoreAnggotaRequest;
use App\Http\Requests\UpdateAnggotaRequest;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    /**
     * Jumlah data per halaman.
     */
    private const PER_PAGE = 10;

    /**
     * Jumlah data riwayat transaksi per halaman (di halaman detail anggota).
     */
    private const RIWAYAT_PER_PAGE = 8;

    /**
     * Tarif denda per hari keterlambatan (harus sinkron dengan Transaksi::getEstimasiDendaAttribute()).
     */
    private const TARIF_DENDA_PER_HARI = 5000;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotas = Anggota::latest()->paginate(self::PER_PAGE);

        // Statistik (global, seluruh data)
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'Aktif')->count();
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();

        // Mengambil daftar unik pekerjaan yang ada di database untuk Dropdown
        $pekerjaanList = $this->getPekerjaanList();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'pekerjaanList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kodeAnggota = $this->generateKodeAnggota();
        return view('anggota.create', compact('kodeAnggota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnggotaRequest $request)
    {
        try {
            Anggota::create($request->validated());

            return redirect()->route('anggota.index')
                ->with('success', 'Data anggota berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan anggota: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * Halaman ini berfungsi sebagai "Dashboard Profil Anggota":
     * - Data Profil     : data dasar anggota (langsung dari model)
     * - Data Statistik  : ringkasan angka peminjaman/pengembalian/denda
     * - Data Riwayat    : daftar transaksi (dengan filter, search, sort, pagination)
     * - Data Timeline   : urutan aktivitas anggota (pinjam/kembali/telat/bayar denda)
     * - Data Insight    : analisis tambahan (buku favorit, kategori favorit, dsb)
     */
    public function show(Request $request, string $id)
    {
        $anggota = Anggota::findOrFail($id);

        // ================================================================
        // 1. DATA STATISTIK
        //    Dihitung langsung dengan agregasi Eloquent (bukan looping koleksi),
        //    supaya tetap ringan walaupun riwayat transaksi anggota banyak.
        // ================================================================
        $statistik = $this->buildStatistik($anggota);

        // ================================================================
        // 2. DATA RIWAYAT PEMINJAMAN
        //    Query dasar + relasi buku (eager load, hindari N+1),
        //    lalu diberi filter status, search, dan sorting sesuai request.
        // ================================================================
        $riwayatQuery = $anggota->transaksis()->with('buku');
        $riwayatQuery = $this->applyRiwayatFilter($riwayatQuery, $request);
        $riwayatQuery = $this->applyRiwayatSort($riwayatQuery, $request);

        $riwayat = $riwayatQuery
            ->paginate(self::RIWAYAT_PER_PAGE)
            ->appends($request->query());

        // ================================================================
        // 3. DATA TIMELINE AKTIVITAS
        //    Dibangun dari seluruh transaksi anggota (tanpa filter/paginate),
        //    supaya riwayat aktivitas tetap lengkap terlepas dari filter tabel.
        // ================================================================
        $semuaTransaksi = $anggota->transaksis()->with('buku')->latest('tanggal_pinjam')->get();
        $timeline = $this->buildTimeline($semuaTransaksi);

        // ================================================================
        // 4. DATA INSIGHT TAMBAHAN
        //    Analisis ringkas berbasis $semuaTransaksi yang sudah dimuat di atas
        //    (tidak query ulang ke database).
        // ================================================================
        $insight = $this->buildInsight($anggota, $semuaTransaksi, $statistik);

        return view('anggota.show', compact(
            'anggota',
            'statistik',
            'riwayat',
            'timeline',
            'insight'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnggotaRequest $request, string $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $anggota->update($request->validated());

            return redirect()->route('anggota.show', $anggota->id)
                ->with('success', 'Data anggota berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate anggota: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $namaAnggota = $anggota->nama;

            $anggota->delete();

            return redirect()->route('anggota.index')
                ->with('success', "Anggota '{$namaAnggota}' berhasil dihapus!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }

    /**
     * Pencarian dan Filter Data Anggota
     */
    public function search(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        // PENTING: hitung statistik SEBELUM paginate(), memakai clone query,
        // supaya angkanya mencerminkan SELURUH hasil filter (bukan cuma 1 halaman).
        $totalAnggota = (clone $query)->count();
        $anggotaAktif = (clone $query)->where('status', 'Aktif')->count();
        $anggotaNonaktif = (clone $query)->where('status', 'Nonaktif')->count();

        $anggotas = $query->latest()
            ->paginate(self::PER_PAGE)
            ->appends($request->query());

        $pekerjaanList = $this->getPekerjaanList();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif',
            'pekerjaanList'
        ));
    }

    /**
     * Helper: membangun query filter/pencarian anggota berdasarkan request.
     */
    private function buildFilterQuery(Request $request)
    {
        $query = Anggota::query();

        // Menggunakan filled() untuk mengecek input dengan lebih aman
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('telepon', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pekerjaan')) {
            $query->where('pekerjaan', $request->pekerjaan);
        }

        // Filter Range Umur - Melakukan konversi dari Umur menjadi rentang Tanggal Lahir
        if ($request->filled('umur_min')) {
            // Contoh: Umur Min 20, berarti tanggal lahir maksimal adalah tepat 20 tahun yang lalu
            $maxDate = Carbon::today()->subYears($request->umur_min)->toDateString();
            $query->whereDate('tanggal_lahir', '<=', $maxDate);
        }

        if ($request->filled('umur_max')) {
            // Contoh: Umur Max 30, berarti belum ulang tahun ke-31, batas tanggal lahir minimal adalah 31 tahun yang lalu + 1 hari
            $minDate = Carbon::today()->subYears($request->umur_max + 1)->addDay()->toDateString();
            $query->whereDate('tanggal_lahir', '>=', $minDate);
        }

        return $query;
    }

    /**
     * Helper: daftar unik pekerjaan untuk dropdown filter.
     */
    private function getPekerjaanList()
    {
        return Anggota::select('pekerjaan')
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->distinct()
            ->orderBy('pekerjaan')
            ->pluck('pekerjaan');
    }

    /**
     * Export Data Anggota ke CSV
     */
    public function export()
    {
        $anggotas = Anggota::all();
        $filename = 'data_anggota_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($anggotas) {
            $file = fopen('php://output', 'w');

            // BOM untuk format UTF-8 (membantu Excel membaca dengan benar)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Kode Anggota',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Umur',
                'Pekerjaan',
                'Email',
                'Telepon',
                'Alamat',
                'Status',
                'Tanggal Daftar'
            ]);

            foreach ($anggotas as $anggota) {
                // Hitung umur untuk diexport jika tanggal lahir tersedia
                $umur = $anggota->tanggal_lahir ? Carbon::parse($anggota->tanggal_lahir)->age : '-';

                fputcsv($file, [
                    $anggota->kode_anggota,
                    $anggota->nama,
                    $anggota->jenis_kelamin,
                    $anggota->tanggal_lahir,
                    $umur,
                    $anggota->pekerjaan ?? '-',
                    $anggota->email,
                    $anggota->telepon,
                    $anggota->alamat,
                    $anggota->status,
                    $anggota->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate Kode Anggota Otomatis (Contoh: ANG-2023-001)
     */
    private function generateKodeAnggota()
    {
        $tahun = date('Y');
        $lastAnggota = Anggota::whereYear('created_at', $tahun)
            ->orderBy('kode_anggota', 'desc')
            ->first();

        if ($lastAnggota) {
            $lastNumber = intval(substr($lastAnggota->kode_anggota, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'ANG-' . $tahun . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // ====================================================================
    // HELPER KHUSUS HALAMAN DETAIL ANGGOTA (show)
    // ====================================================================

    /**
     * Bangun ringkasan statistik peminjaman anggota memakai agregasi SQL
     * (selectRaw + groupBy), bukan menghitung manual dari koleksi PHP.
     */
    private function buildStatistik(Anggota $anggota): array
    {
        // Satu query agregat untuk angka-angka utama, agar hemat round-trip ke DB.
        $agg = Transaksi::where('anggota_id', $anggota->id)
            ->selectRaw("
                COUNT(*) as total_peminjaman,
                SUM(CASE WHEN status = 'Dikembalikan' THEN 1 ELSE 0 END) as total_pengembalian,
                SUM(CASE WHEN status = 'Dipinjam' THEN 1 ELSE 0 END) as sedang_dipinjam,
                SUM(denda) as total_denda_final
            ")
            ->first();

        // Jumlah transaksi yang TERLAMBAT (baik masih dipinjam & lewat tempo,
        // maupun sudah dikembalikan namun telat) — pakai scope yang sudah ada
        // untuk yang masih dipinjam, plus kondisi untuk yang sudah dikembalikan.
        $totalTerlambatDipinjam = (clone $anggota->transaksis())->terlambat()->count();

        $totalTerlambatDikembalikan = Transaksi::where('anggota_id', $anggota->id)
            ->where('status', 'Dikembalikan')
            ->whereColumn('tanggal_dikembalikan', '>', 'tanggal_kembali')
            ->count();

        $totalTerlambat = $totalTerlambatDipinjam + $totalTerlambatDikembalikan;

        // Estimasi denda realtime untuk transaksi yang MASIH dipinjam & telat
        // (belum tercatat di kolom `denda` karena belum final).
        $estimasiDendaBerjalan = (clone $anggota->transaksis())
            ->terlambat()
            ->get()
            ->sum(fn($t) => $t->estimasi_denda);

        // Total hari meminjam & rata-rata lama meminjam, dihitung di level SQL
        // dengan DATEDIFF agar tidak perlu memuat semua baris ke PHP.
        $durasi = Transaksi::where('anggota_id', $anggota->id)
            ->selectRaw("
                SUM(DATEDIFF(COALESCE(tanggal_dikembalikan, CURDATE()), tanggal_pinjam)) as total_hari_meminjam,
                AVG(DATEDIFF(COALESCE(tanggal_dikembalikan, CURDATE()), tanggal_pinjam)) as rata_rata_hari
            ")
            ->first();

        $peminjamanTahunIni = Transaksi::where('anggota_id', $anggota->id)
            ->whereYear('tanggal_pinjam', now()->year)
            ->count();

        // Buku favorit: buku yang paling sering dipinjam oleh anggota ini.
        $bukuFavorit = Transaksi::where('anggota_id', $anggota->id)
            ->selectRaw('buku_id, COUNT(*) as jumlah_pinjam')
            ->groupBy('buku_id')
            ->orderByDesc('jumlah_pinjam')
            ->with('buku')
            ->first();

        return [
            'total_peminjaman'       => (int) $agg->total_peminjaman,
            'total_pengembalian'     => (int) $agg->total_pengembalian,
            'sedang_dipinjam'        => (int) $agg->sedang_dipinjam,
            'total_terlambat'        => (int) $totalTerlambat,
            'total_denda_final'      => (float) $agg->total_denda_final,
            'estimasi_denda_berjalan' => (float) $estimasiDendaBerjalan,
            'total_denda'            => (float) $agg->total_denda_final + (float) $estimasiDendaBerjalan,
            'total_hari_meminjam'    => (int) ($durasi->total_hari_meminjam ?? 0),
            'rata_rata_hari'         => round($durasi->rata_rata_hari ?? 0, 1),
            'peminjaman_tahun_ini'   => $peminjamanTahunIni,
            'buku_favorit'           => $bukuFavorit?->buku,
            'buku_favorit_jumlah'    => $bukuFavorit->jumlah_pinjam ?? 0,
        ];
    }

    /**
     * Terapkan filter status, terlambat, dan pencarian judul buku
     * pada query riwayat transaksi anggota.
     */
    private function applyRiwayatFilter($query, Request $request)
    {
        // Filter status: all | Dipinjam | Dikembalikan | Terlambat
        // "Terlambat" bukan kolom status di DB, jadi ditangani sebagai kondisi khusus.
        if ($request->filled('status') && $request->status !== 'Semua') {
            if ($request->status === 'Terlambat') {
                $query->where(function ($q) {
                    $q->where(function ($qq) {
                        // Masih dipinjam & sudah lewat tanggal kembali
                        $qq->where('status', 'Dipinjam')
                            ->whereDate('tanggal_kembali', '<', now());
                    })->orWhere(function ($qq) {
                        // Sudah dikembalikan tapi telat
                        $qq->where('status', 'Dikembalikan')
                            ->whereColumn('tanggal_dikembalikan', '>', 'tanggal_kembali');
                    });
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search: cari berdasarkan judul buku atau kode transaksi
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_transaksi', 'like', "%{$keyword}%")
                    ->orWhereHas('buku', function ($qb) use ($keyword) {
                        $qb->where('judul', 'like', "%{$keyword}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Terapkan sorting pada query riwayat transaksi anggota.
     */
    private function applyRiwayatSort($query, Request $request)
    {
        $sort = $request->get('sort', 'terbaru');

        return match ($sort) {
            'terlama' => $query->orderBy('tanggal_pinjam', 'asc'),
            'denda'   => $query->orderByDesc('denda'),
            // Sort berdasarkan judul buku memakai subquery agar aman dipakai
            // bersama with()/paginate() tanpa risiko kolom ambigu dari join.
            'judul'   => $query->orderBy(
                \App\Models\Buku::select('judul')
                    ->whereColumn('buku.id', 'transaksis.buku_id')
                    ->limit(1)
            ),
            default   => $query->orderByDesc('tanggal_pinjam'), // 'terbaru'
        };
    }

    /**
     * Bangun timeline aktivitas anggota dari koleksi transaksi.
     * Setiap transaksi bisa menghasilkan 1-3 event: pinjam, kembali (tepat waktu
     * atau telat), dan bayar denda (jika ada denda tercatat).
     */
    private function buildTimeline($transaksis): array
    {
        $events = [];

        foreach ($transaksis as $t) {
            // Event: Meminjam buku
            $events[] = [
                'tanggal'  => $t->tanggal_pinjam,
                'tipe'     => 'pinjam',
                'icon'     => '📚',
                'warna'    => 'blue',
                'judul'    => 'Meminjam buku',
                'deskripsi' => $t->buku->judul ?? 'Buku tidak ditemukan',
                'kode'     => $t->kode_transaksi,
            ];

            // Event: Mengembalikan buku (jika sudah dikembalikan)
            if ($t->tanggal_dikembalikan) {
                $telat = $t->tanggal_dikembalikan->gt($t->tanggal_kembali);

                $events[] = [
                    'tanggal'  => $t->tanggal_dikembalikan,
                    'tipe'     => $telat ? 'telat' : 'kembali',
                    'icon'     => $telat ? '⚠️' : '✅',
                    'warna'    => $telat ? 'amber' : 'emerald',
                    'judul'    => $telat ? 'Mengembalikan buku (terlambat)' : 'Mengembalikan buku',
                    'deskripsi' => $t->buku->judul ?? 'Buku tidak ditemukan',
                    'kode'     => $t->kode_transaksi,
                ];

                // Event: Membayar denda (jika ada nilai denda tercatat)
                if ($t->denda > 0) {
                    $events[] = [
                        'tanggal'  => $t->tanggal_dikembalikan,
                        'tipe'     => 'denda',
                        'icon'     => '💰',
                        'warna'    => 'red',
                        'judul'    => 'Membayar denda',
                        'deskripsi' => 'Rp ' . number_format($t->denda, 0, ',', '.') . ' — ' . ($t->buku->judul ?? ''),
                        'kode'     => $t->kode_transaksi,
                    ];
                }
            }
        }

        // Urutkan dari yang paling baru
        usort($events, fn($a, $b) => $b['tanggal'] <=> $a['tanggal']);

        return $events;
    }

    /**
     * Bangun insight tambahan berbasis data transaksi anggota.
     */
    private function buildInsight(Anggota $anggota, $transaksis, array $statistik): array
    {
        $totalSelesai = $statistik['total_pengembalian'];

        // Persentase pengembalian tepat waktu vs terlambat, dihitung dari
        // transaksi yang statusnya sudah "Dikembalikan".
        $selesai = $transaksis->where('status', 'Dikembalikan');
        $selesaiTepatWaktu = $selesai->filter(function ($t) {
            return !$t->tanggal_dikembalikan || $t->tanggal_dikembalikan->lte($t->tanggal_kembali);
        })->count();

        $persenTepatWaktu = $totalSelesai > 0
            ? round(($selesaiTepatWaktu / $totalSelesai) * 100, 1)
            : 0;

        $persenTerlambat = $totalSelesai > 0
            ? round(100 - $persenTepatWaktu, 1)
            : 0;

        // Kategori favorit: kategori buku yang paling sering muncul di riwayat.
        $kategoriFavorit = $transaksis
            ->pluck('buku.kategori')
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();

        $aktivitasTerakhir = $transaksis->first(); // sudah diurutkan latest('tanggal_pinjam')

        return [
            'kategori_favorit'     => $kategoriFavorit ?? '-',
            'persen_tepat_waktu'   => $persenTepatWaktu,
            'persen_terlambat'     => $persenTerlambat,
            'lama_menjadi_anggota' => $anggota->lama_anggota, // sudah ada accessor di model
            'aktivitas_terakhir'   => $aktivitasTerakhir,
        ];
    }
}
