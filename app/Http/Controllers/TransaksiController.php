<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Durasi peminjaman default (dalam hari).
     */
    private const DURASI_PINJAM_HARI = 7;

    /**
     * Menampilkan daftar semua transaksi dengan pencarian, filter, dan statistik.
     */
    public function index(Request $request)
    {
        $transaksis = $this->filteredTransaksi($request);
        $anggotasList = Anggota::orderBy('nama')->get();

        // Mengambil data statistik untuk dashboard ringkas transaksi
        $totalTransaksi = Transaksi::count();
        $totalDipinjam = Transaksi::where('status', 'Dipinjam')->count();
        $totalTerlambat = Transaksi::where('status', 'Dipinjam')
            ->whereDate('tanggal_kembali', '<', Carbon::now()->toDateString())
            ->count();

        return view('transaksi.index', compact(
            'transaksis',
            'anggotasList',
            'totalTransaksi',
            'totalDipinjam',
            'totalTerlambat'
        ));
    }

    /**
     * Helper untuk memproses pencarian & filter multi-kriteria secara dinamis.
     */
    private function filteredTransaksi(Request $request)
    {
        $query = Transaksi::with(['buku', 'anggota']);

        // 1. Filter Kata Kunci (Kode Transaksi, Nama Anggota, atau Judul Buku)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_transaksi', 'like', "%{$keyword}%")
                    ->orWhereHas('anggota', function ($qA) use ($keyword) {
                        $qA->where('nama', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('buku', function ($qB) use ($keyword) {
                        $qB->where('judul', 'like', "%{$keyword}%");
                    });
            });
        }

        // 2. Filter Status (Termasuk kondisi khusus "Terlambat")
        if ($request->filled('status')) {
            if ($request->status === 'Terlambat') {
                $query->where('status', 'Dipinjam')
                    ->whereDate('tanggal_kembali', '<', Carbon::now()->toDateString());
            } else {
                $query->where('status', $request->status);
            }
        }

        // 3. Filter berdasarkan Anggota tertentu
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        // 4. Filter Rentang Tanggal Pinjam (Dari)
        if ($request->filled('tanggal_pinjam_start')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_pinjam_start);
        }

        // 5. Filter Rentang Tanggal Pinjam (Sampai)
        if ($request->filled('tanggal_pinjam_end')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_pinjam_end);
        }

        // Ambil data transaksi beserta paginasi
        $transaksis = $query->latest()->paginate(10);

        // Transformasi data untuk perhitungan denda & keterlambatan secara dinamis
        $transaksis->getCollection()->transform(function ($transaksi) {
            $transaksi->tanggal_pinjam = Carbon::parse($transaksi->tanggal_pinjam);
            $transaksi->tanggal_kembali = Carbon::parse($transaksi->tanggal_kembali);

            if ($transaksi->status === 'Dipinjam') {
                $hariTerlambat = $transaksi->tanggal_kembali->diffInDays(now(), false);
                $transaksi->terlambat = $hariTerlambat > 0 ? (int)$hariTerlambat : 0;
                $transaksi->estimasi_denda = $transaksi->terlambat * 5000;
            } else {
                $transaksi->terlambat = 0;
                $transaksi->estimasi_denda = $transaksi->denda;
            }
            return $transaksi;
        });

        return $transaksis;
    }

    /**
     * Menampilkan formulir pendaftaran transaksi pinjam baru.
     */
    public function create()
    {
        $kodeTransaksi = $this->generateKodeTransaksi();
        $bukus = Buku::where('stok', '>', 0)->get();
        $anggotas = Anggota::where('status', 'Aktif')->get();

        return view('transaksi.create', compact('kodeTransaksi', 'bukus', 'anggotas'));
    }

    /**
     * Menyimpan transaksi peminjaman baru ke database.
     * Catatan: tanggal_kembali TIDAK diinput manual oleh user, tetapi
     * dihitung otomatis = tanggal_pinjam + 7 hari (sesuai aturan peminjaman).
     */
    public function store(Request $request)
    {
        // Validasi input data dengan mencocokkan nama tabel singular (anggota & buku)
        $request->validate([
            'anggota_id'      => 'required|exists:anggota,id',
            'buku_id'         => 'required|exists:buku,id',
            'tanggal_pinjam'  => 'required|date',
            'keterangan'      => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Hitung otomatis tanggal kembali (7 hari dari tanggal pinjam)
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)
                    ->addDays(self::DURASI_PINJAM_HARI);

                Transaksi::create([
                    'kode_transaksi'  => $this->generateKodeTransaksi(),
                    'anggota_id'      => $request->anggota_id,
                    'buku_id'         => $request->buku_id,
                    'tanggal_pinjam'  => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'keterangan'      => $request->keterangan,
                    'status'          => 'Dipinjam',
                    'denda'           => 0,
                ]);

                // Kurangi stok buku karena sedang dipinjam
                Buku::where('id', $request->buku_id)->decrement('stok');
            });

            // Redirect ke halaman index dengan status sukses agar SweetAlert muncul
            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi peminjaman berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan informasi detail satu transaksi secara spesifik.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['buku', 'anggota'])->findOrFail($id);

        $transaksi->tanggal_pinjam = Carbon::parse($transaksi->tanggal_pinjam);
        $transaksi->tanggal_kembali = Carbon::parse($transaksi->tanggal_kembali);

        if ($transaksi->status === 'Dipinjam') {
            $hariTerlambat = $transaksi->tanggal_kembali->diffInDays(now(), false);
            $transaksi->terlambat = $hariTerlambat > 0 ? (int)$hariTerlambat : 0;
            $transaksi->estimasi_denda = $transaksi->terlambat * 5000;
        }

        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Memproses Pengembalian Buku dan menghitung total denda jika terlambat.
     */
    public function kembalikan($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status === 'Dikembalikan') {
            return redirect()->route('transaksi.index')
                ->with('error', 'Buku pada transaksi ini sudah pernah dikembalikan.');
        }

        try {
            DB::transaction(function () use ($transaksi) {
                $tanggalDikembalikan = now();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                $transaksi->update([
                    'status'               => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda'                => $denda,
                ]);

                // Kembalikan stok buku bertambah 1
                $transaksi->buku->increment('stok');
            });

            // Redirect kembali ke daftar transaksi utama dengan pesan sukses SweetAlert
            return redirect()->route('transaksi.index')
                ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus Data Transaksi yang ada.
     */
    public function destroy(string $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Jika status masih dipinjam, kembalikan stok buku sebelum data transaksi dihapus
            if ($transaksi->status === 'Dipinjam') {
                $transaksi->buku->increment('stok');
            }

            $transaksi->delete();

            return redirect()->route('transaksi.index')
                ->with('success', 'Data transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Export PDF Laporan Transaksi secara keseluruhan atau berdasarkan filter.
     */
    public function laporanExport(Request $request)
    {
        $query = Transaksi::with(['buku', 'anggota']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_transaksi', 'like', "%{$keyword}%")
                    ->orWhereHas('anggota', function ($qA) use ($keyword) {
                        $qA->where('nama', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('buku', function ($qB) use ($keyword) {
                        $qB->where('judul', 'like', "%{$keyword}%");
                    });
            });
        }
        if ($request->filled('status')) {
            if ($request->status === 'Terlambat') {
                $query->where('status', 'Dipinjam')->whereDate('tanggal_kembali', '<', Carbon::now()->toDateString());
            } else {
                $query->where('status', $request->status);
            }
        }
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }
        if ($request->filled('tanggal_pinjam_start')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_pinjam_start);
        }
        if ($request->filled('tanggal_pinjam_end')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_pinjam_end);
        }

        $transaksis = $query->latest()->get();

        // Format tanggal & kalkulasi denda untuk export PDF
        $transaksis->transform(function ($transaksi) {
            $transaksi->tanggal_pinjam = Carbon::parse($transaksi->tanggal_pinjam);
            $transaksi->tanggal_kembali = Carbon::parse($transaksi->tanggal_kembali);

            if ($transaksi->status === 'Dipinjam') {
                $hariTerlambat = $transaksi->tanggal_kembali->diffInDays(now(), false);
                $transaksi->terlambat = $hariTerlambat > 0 ? (int)$hariTerlambat : 0;
                $transaksi->estimasi_denda = $transaksi->terlambat * 5000;
            } else {
                $transaksi->terlambat = 0;
                $transaksi->estimasi_denda = $transaksi->denda;
            }
            return $transaksi;
        });

        $pdf = Pdf::loadView('transaksi.pdf', compact('transaksis'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan_transaksi_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Menghasilkan kode transaksi otomatis secara urut (Auto-increment format).
     */
    private function generateKodeTransaksi()
    {
        $lastTransaksi = Transaksi::latest()->first();
        $newNumber     = $lastTransaksi ? intval(substr($lastTransaksi->kode_transaksi, -3)) + 1 : 1;

        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung denda keterlambatan secara otomatis (Rp 5.000 per hari).
     */
    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        $tanggalKembali = Carbon::parse($transaksi->tanggal_kembali);
        $hariTerlambat = $tanggalKembali->diffInDays($tanggalDikembalikan, false);

        return $hariTerlambat > 0 ? ((int)$hariTerlambat * 5000) : 0;
    }
}
