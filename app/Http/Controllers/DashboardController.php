<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Periode default yang dipakai saat dashboard pertama kali dibuka
     * (sebelum user memilih filter apa pun).
     */
    private const DEFAULT_PERIOD = '30hari';

    /**
     * Daftar periode yang valid, dipakai untuk validasi request AJAX.
     */
    private const VALID_PERIODS = ['7hari', '30hari', '3bulan', '6bulan', '1tahun', 'semua'];

    public function index(Request $request)
    {
        // Periode filter (kalau halaman di-reload dengan query ?period=..., pakai itu;
        // kalau tidak, pakai default). Ini opsional — filter utamanya tetap jalan lewat AJAX.
        $period = $this->normalizePeriod($request->get('period', self::DEFAULT_PERIOD));

        // ==========================================
        // 1. PAYLOAD YANG MENGIKUTI FILTER PERIODE
        //    (statistik transaksi + 4 chart baru)
        //    Dipakai untuk render awal DAN dikirim ke Blade sebagai JSON
        //    supaya chart bisa langsung digambar tanpa perlu AJAX call pertama.
        // ==========================================
        $periodPayload = $this->buildPeriodPayload($period);

        // ==========================================
        // 2. STATISTIK GLOBAL / SNAPSHOT (TIDAK bergantung periode)
        //    Dipertahankan persis dari kode lama Anda.
        // ==========================================
        $countTerlambat = Transaksi::where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->count();

        $stats = [
            'total_buku'         => Buku::count(),
            'total_anggota'      => Anggota::where('status', 'Aktif')->count(),
            'total_transaksi'    => Transaksi::count(),
            'sedang_dipinjam'    => Transaksi::where('status', 'Dipinjam')->count(),
            'terlambat'          => $countTerlambat,
            'transaksi_hari_ini' => Transaksi::whereDate('tanggal_pinjam', today())->count(),
            'buku_tersedia'      => Buku::where('stok', '>', 0)->count(),
        ];

        $totalBuku       = $stats['total_buku'];
        $bukuTersedia    = $stats['buku_tersedia'];
        $bukuHabis       = Buku::where('stok', 0)->count();

        $totalAnggota    = Anggota::count();
        $anggotaAktif    = $stats['total_anggota'];
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();

        // ==========================================
        // 3. TREN 7 HARI TERAKHIR (mini bar chart lama, dipertahankan apa adanya)
        // ==========================================
        $trenPeminjaman = $this->getTrenPeminjaman();

        // ==========================================
        // 4. DATA POPULER & AKTIF (TIDAK terikat filter periode — daftar "top 5" all-time,
        //    dipertahankan dari kode lama supaya tidak menghapus fitur)
        // ==========================================
        $bukuPopuler = Buku::withCount('transaksis')
            ->orderByDesc('transaksis_count')
            ->take(5)->get();

        $anggotaAktifList = Anggota::withCount('transaksis')
            ->orderByDesc('transaksis_count')
            ->take(5)->get();

        // ==========================================
        // 5. DATA TERBARU & KETERLAMBATAN (dipertahankan dari kode lama)
        // ==========================================
        $bukuTerbaru    = Buku::latest()->take(5)->get();
        $anggotaTerbaru = Anggota::latest()->take(5)->get();

        $recentTransaksi = Transaksi::with(['anggota', 'buku'])
            ->latest()->take(5)->get();

        $totalTerlambat = $stats['terlambat'];
        $listTerlambat  = Transaksi::with(['anggota', 'buku'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->latest()
            ->take(5)
            ->get();

        // ==========================================
        // 6. RETURN VIEW
        // ==========================================
        $viewPath = view()->exists('dashboard.index') ? 'dashboard.index' : 'dashboard';

        return view($viewPath, compact(
            'stats',
            'period',
            'periodPayload',
            'bukuPopuler',
            'recentTransaksi',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'totalAnggota',
            'anggotaAktif',
            'anggotaAktifList',
            'anggotaNonaktif',
            'bukuTerbaru',
            'anggotaTerbaru',
            'trenPeminjaman',
            'totalTerlambat',
            'listTerlambat'
        ));
    }

    /**
     * ENDPOINT AJAX BARU.
     * Dipanggil dari JavaScript (fetch) setiap kali user mengganti filter periode.
     * Mengembalikan JSON berisi statistik + data 4 chart untuk periode yang diminta,
     * tanpa reload halaman.
     *
     * Route yang perlu ditambahkan (lihat instruksi di bawah):
     *   GET /dashboard/data  ->  DashboardController@data  ->  name('dashboard.data')
     */
    public function data(Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:' . implode(',', self::VALID_PERIODS),
        ]);

        $period = $this->normalizePeriod($request->get('period', self::DEFAULT_PERIOD));

        return response()->json($this->buildPeriodPayload($period));
    }

    public function exportPdf(Request $request)
    {
        // ==== TAMBAHAN: bangun periodPayload sama seperti index() ====
        $period = $this->normalizePeriod($request->get('period', self::DEFAULT_PERIOD));
        $periodPayload = $this->buildPeriodPayload($period);
        // ===============================================================

        // 1. Data Utama (statistik snapshot, sama seperti index())
        $countTerlambat = Transaksi::where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->count();

        $stats = [
            'total_buku'         => Buku::count(),
            'total_anggota'      => Anggota::where('status', 'Aktif')->count(),
            'total_transaksi'    => Transaksi::count(),
            'sedang_dipinjam'    => Transaksi::where('status', 'Dipinjam')->count(),
            'terlambat'          => $countTerlambat,
            'denda_bulan_ini'    => Transaksi::whereMonth('tanggal_dikembalikan', now()->month)
                ->whereYear('tanggal_dikembalikan', now()->year)
                ->sum('denda'),
            'transaksi_hari_ini' => Transaksi::whereDate('tanggal_pinjam', today())->count(),
            'buku_tersedia'      => Buku::where('stok', '>', 0)->count(),
        ];

        // 2. Tarik Data List (yang muncul di PDF)
        $recentTransaksi = Transaksi::with(['anggota', 'buku'])->latest()->take(5)->get();

        $listTerlambat = Transaksi::with(['anggota', 'buku'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->latest()
            ->get();

        // 3. Tarik Data TAMBAHAN untuk melengkapi Dashboard PDF
        $bukuPopuler    = Buku::withCount('transaksis')->orderByDesc('transaksis_count')->take(5)->get();
        $bukuTerbaru    = Buku::latest()->take(5)->get();
        $anggotaTerbaru = Anggota::latest()->take(5)->get();

        // 4. Masukkan ke View dengan semua variabel tersebut
        $pdf = Pdf::loadView('dashboard.laporan-pdf', compact(
            'stats',
            'period',          // <-- TAMBAHAN
            'periodPayload',   // <-- TAMBAHAN
            'recentTransaksi',
            'listTerlambat',
            'bukuPopuler',
            'bukuTerbaru',
            'anggotaTerbaru'
        ));

        return $pdf->download('laporan-lengkap-dashboard.pdf');
    }

    /**
     * Hitung jumlah transaksi peminjaman per hari untuk 7 hari terakhir.
     * (Dipertahankan dari kode lama Anda — dipakai untuk mini bar chart terpisah
     * dari filter periode global, karena rentangnya memang selalu 7 hari.)
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

    // ==================================================================
    // ============  BAGIAN BARU: LOGIKA FILTER PERIODE GLOBAL  ==========
    // ==================================================================

    /**
     * Pastikan nilai period yang datang dari request valid; fallback ke default.
     */
    private function normalizePeriod(?string $period): string
    {
        return in_array($period, self::VALID_PERIODS, true) ? $period : self::DEFAULT_PERIOD;
    }

    /**
     * Menerjemahkan kode periode ("7hari", "30hari", dst) menjadi:
     * - tanggal mulai ($start, null artinya "sejak data paling awal")
     * - tanggal akhir ($end, selalu "sekarang")
     * - granularitas grouping untuk line chart tren ('daily'|'weekly'|'monthly'|'yearly')
     *
     * Aturan granularitas sesuai instruksi:
     *   7 Hari / 30 Hari -> harian
     *   3 Bulan           -> mingguan
     *   6 Bulan / 1 Tahun -> bulanan
     *   Semua             -> tahunan
     */
    private function resolvePeriodRange(string $period): array
    {
        $end = Carbon::now();

        switch ($period) {
            case '7hari':
                return [Carbon::now()->subDays(6)->startOfDay(), $end, 'daily'];
            case '30hari':
                return [Carbon::now()->subDays(29)->startOfDay(), $end, 'daily'];
            case '3bulan':
                return [Carbon::now()->subMonths(3)->startOfDay(), $end, 'weekly'];
            case '6bulan':
                return [Carbon::now()->subMonths(6)->startOfDay(), $end, 'monthly'];
            case '1tahun':
                return [Carbon::now()->subYear()->startOfDay(), $end, 'monthly'];
            case 'semua':
            default:
                return [null, $end, 'yearly'];
        }
    }

    /**
     * Method utama: membangun SELURUH data (statistik + 4 chart) untuk satu periode.
     * Dipakai baik oleh index() (render awal) maupun data() (AJAX).
     */
    private function buildPeriodPayload(string $period): array
    {
        [$start, $end, $groupBy] = $this->resolvePeriodRange($period);

        return [
            'period'  => $period,
            'stats'   => $this->getPeriodStats($start, $end),
            'charts'  => [
                'kategoriBuku'     => $this->getKategoriBukuChart($start, $end),
                'statusTransaksi'  => $this->getStatusTransaksiChart($start, $end),
                'topBuku'          => $this->getTopBukuChart($start, $end),
                'trend'            => $this->getTrendChart($start, $end, $groupBy),
            ],
        ];
    }

    /**
     * Statistik transaksi yang genuinely bergantung pada periode filter:
     * jumlah transaksi & total denda dalam rentang waktu yang dipilih.
     */
    private function getPeriodStats(?Carbon $start, Carbon $end): array
    {
        $transaksiQuery = Transaksi::query();
        if ($start) {
            $transaksiQuery->whereBetween('tanggal_pinjam', [$start, $end]);
        }
        $totalTransaksiPeriode = (clone $transaksiQuery)->count();

        $dendaQuery = Transaksi::query();
        if ($start) {
            $dendaQuery->whereBetween('tanggal_dikembalikan', [$start, $end]);
        } else {
            $dendaQuery->whereNotNull('tanggal_dikembalikan');
        }
        $dendaPeriode = (clone $dendaQuery)->sum('denda');

        return [
            'total_transaksi_periode' => $totalTransaksiPeriode,
            'denda_periode'           => (int) $dendaPeriode,
        ];
    }

    /**
     * PIE CHART: Distribusi peminjaman per kategori buku, dalam periode terpilih.
     * Menggunakan withCount() dengan subquery constraint tanggal (bukan raw join),
     * jadi tetap aman dari N+1 dan tidak menyentuh nama tabel secara manual.
     */
    private function getKategoriBukuChart(?Carbon $start, Carbon $end): array
    {
        $bukus = Buku::withCount(['transaksis as jumlah_pinjam' => function ($q) use ($start, $end) {
            if ($start) {
                $q->whereBetween('tanggal_pinjam', [$start, $end]);
            }
        }])->get();

        $grouped = $bukus->groupBy('kategori')
            ->map(fn($group) => $group->sum('jumlah_pinjam'))
            ->filter(fn($jumlah) => $jumlah > 0)
            ->sortDesc();

        return [
            'labels' => $grouped->keys()->values(),
            'data'   => $grouped->values(),
        ];
    }

    /**
     * DONUT CHART: Status transaksi (Dipinjam / Dikembalikan / Terlambat)
     * dalam periode terpilih (berdasarkan tanggal_pinjam).
     */
    private function getStatusTransaksiChart(?Carbon $start, Carbon $end): array
    {
        $base = Transaksi::query();
        if ($start) {
            $base->whereBetween('tanggal_pinjam', [$start, $end]);
        }

        $dikembalikan = (clone $base)->where('status', 'Dikembalikan')->count();

        $terlambat = (clone $base)
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->count();

        $dipinjamTepatWaktu = (clone $base)
            ->where('status', 'Dipinjam')
            ->where('tanggal_kembali', '>=', now())
            ->count();

        return [
            'labels' => ['Dipinjam', 'Dikembalikan', 'Terlambat'],
            'data'   => [$dipinjamTepatWaktu, $dikembalikan, $terlambat],
            'total'  => $dipinjamTepatWaktu + $dikembalikan + $terlambat,
        ];
    }

    /**
     * HORIZONTAL BAR CHART: Top 10 buku terpopuler (jumlah peminjaman), dalam periode terpilih.
     */
    private function getTopBukuChart(?Carbon $start, Carbon $end): array
    {
        $bukus = Buku::withCount(['transaksis as jumlah_pinjam' => function ($q) use ($start, $end) {
            if ($start) {
                $q->whereBetween('tanggal_pinjam', [$start, $end]);
            }
        }])
            ->orderByDesc('jumlah_pinjam')
            ->take(10)
            ->get()
            ->filter(fn($b) => $b->jumlah_pinjam > 0);

        return [
            'labels' => $bukus->pluck('judul')
                ->map(fn($judul) => strlen($judul) > 28 ? substr($judul, 0, 28) . '...' : $judul)
                ->values(),
            'data' => $bukus->pluck('jumlah_pinjam')->values(),
        ];
    }

    /**
     * LINE CHART: Tren peminjaman vs pengembalian, di-grouping sesuai granularitas periode
     * (harian / mingguan / bulanan / tahunan).
     *
     * Query dioptimasi memakai selectRaw() + groupBy() (1 query per garis, bukan per-titik),
     * lalu di-merge dengan daftar bucket waktu supaya titik yang kosong tetap tampil sebagai 0.
     */
    private function getTrendChart(?Carbon $start, Carbon $end, string $groupBy): array
    {
        $buckets = $this->buildBuckets($start, $end, $groupBy);

        $pinjamAgg  = $this->aggregateByPeriod('tanggal_pinjam', $start, $end, $groupBy);
        $kembaliAgg = $this->aggregateByPeriod('tanggal_dikembalikan', $start, $end, $groupBy);

        $labels = [];
        $pinjam = [];
        $kembali = [];

        foreach ($buckets as $bucket) {
            $labels[]  = $bucket['label'];
            $pinjam[]  = (int) ($pinjamAgg[$bucket['key']] ?? 0);
            $kembali[] = (int) ($kembaliAgg[$bucket['key']] ?? 0);
        }

        return compact('labels', 'pinjam', 'kembali');
    }

    /**
     * Agregasi COUNT(*) transaksi per bucket waktu, memakai satu query
     * (selectRaw + groupBy), untuk 1 kolom tanggal tertentu.
     */
    private function aggregateByPeriod(string $column, ?Carbon $start, Carbon $end, string $groupBy)
    {
        $format = $this->mysqlDateFormat($groupBy);

        $query = Transaksi::query();
        if ($start) {
            $query->whereBetween($column, [$start, $end]);
        } else {
            $query->whereNotNull($column);
        }

        return $query
            ->selectRaw("DATE_FORMAT($column, '{$format}') as periode, COUNT(*) as jumlah")
            ->groupBy('periode')
            ->pluck('jumlah', 'periode');
    }

    /**
     * Format DATE_FORMAT() MySQL yang dipakai per granularitas.
     * Sengaja dibuat agar hasilnya cocok 1:1 dengan format key bucket PHP (lihat buildBuckets()),
     * supaya proses pencocokan (merge) tidak meleset.
     */
    private function mysqlDateFormat(string $groupBy): string
    {
        return match ($groupBy) {
            'daily'   => '%Y-%m-%d',
            'weekly'  => '%x-%v', // ISO year - ISO week
            'monthly' => '%Y-%m',
            'yearly'  => '%Y',
        };
    }

    /**
     * Membangun daftar "bucket" waktu (key + label tampilan) dari $start s/d $end,
     * sesuai granularitas. Kalau $start null (periode "Semua"), bucket dimulai dari
     * tahun transaksi paling awal yang ada di database.
     */
    private function buildBuckets(?Carbon $start, Carbon $end, string $groupBy): array
    {
        if (!$start) {
            $earliest = Transaksi::min('tanggal_pinjam');
            $start = $earliest ? Carbon::parse($earliest)->startOfYear() : $end->copy()->startOfYear();
        }

        $buckets = [];
        $cursor = $start->copy();

        // Batas pengaman supaya loop tidak jalan tanpa henti pada data yang tidak wajar
        $maxIterasi = 500;

        while ($cursor->lte($end) && $maxIterasi-- > 0) {
            switch ($groupBy) {
                case 'daily':
                    $buckets[] = [
                        'key'   => $cursor->format('Y-m-d'),
                        'label' => $cursor->translatedFormat('d M'),
                    ];
                    $cursor->addDay();
                    break;

                case 'weekly':
                    $buckets[] = [
                        'key'   => $cursor->format('o-W'),
                        'label' => 'Mgu ' . $cursor->format('W'),
                    ];
                    $cursor->addWeek();
                    break;

                case 'monthly':
                    $buckets[] = [
                        'key'   => $cursor->format('Y-m'),
                        'label' => $cursor->translatedFormat('M Y'),
                    ];
                    $cursor->addMonth();
                    break;

                case 'yearly':
                default:
                    $buckets[] = [
                        'key'   => $cursor->format('Y'),
                        'label' => $cursor->format('Y'),
                    ];
                    $cursor->addYear();
                    break;
            }
        }

        return $buckets;
    }
}
