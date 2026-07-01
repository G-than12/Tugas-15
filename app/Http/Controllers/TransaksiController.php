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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transaksis = $this->filteredTransaksi($request);
        $anggotas   = Anggota::orderBy('nama')->get();

        // Lakukan mapping pada data transaksi untuk memastikan format tanggal
        // dan variabel perhitungan terlambat & estimasi denda terisi dengan benar.
        $transaksis->each(function ($transaksi) {
            // Pastikan tanggal pinjam dan kembali diubah menjadi instance Carbon
            $transaksi->tanggal_pinjam = Carbon::parse($transaksi->tanggal_pinjam);
            $transaksi->tanggal_kembali = Carbon::parse($transaksi->tanggal_kembali);

            if ($transaksi->status === 'Dipinjam') {
                // Hitung selisih hari keterlambatan terhadap hari ini
                $hariTerlambat = $transaksi->tanggal_kembali->diffInDays(now(), false);
                $transaksi->terlambat = $hariTerlambat > 0 ? (int)$hariTerlambat : 0;
                $transaksi->estimasi_denda = $transaksi->terlambat * 5000;
            } else {
                // Jika sudah dikembalikan, parse tanggal dikembalikan dan hitung keterlambatan saat pengembalian
                $tanggalDikembalikan = Carbon::parse($transaksi->tanggal_dikembalikan);
                $transaksi->tanggal_dikembalikan = $tanggalDikembalikan;

                $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);
                $transaksi->terlambat = $hariTerlambat > 0 ? (int)$hariTerlambat : 0;
                $transaksi->estimasi_denda = 0; // Transaksi yang selesai menggunakan denda statis di DB
            }
        });

        // 1. Hitung jumlah transaksi dengan status 'Dipinjam' (disamakan dengan variabel di view index)
        $statDipinjam = $transaksis->where('status', 'Dipinjam')->count();

        // 2. Hitung jumlah transaksi dengan status 'Dikembalikan' (disamakan dengan variabel di view index)
        $statDikembalikan = $transaksis->where('status', 'Dikembalikan')->count();

        // 3. Hitung total denda (Denda real yang sudah dicatat + Estimasi denda berjalan)
        $statDenda = $transaksis->sum(function ($transaksi) {
            if ($transaksi->status === 'Dikembalikan') {
                return $transaksi->denda ?? 0;
            } else {
                return $transaksi->estimasi_denda ?? 0;
            }
        });

        // Kirim data transaksi beserta variabel statistik yang sesuai dengan view index Anda
        return view('transaksi.index', compact(
            'transaksis',
            'anggotas',
            'statDipinjam',
            'statDikembalikan',
            'statDenda'
        ));
    }

    /**
     * Helper: ambil data transaksi sesuai filter.
     * Dipakai bersama oleh index(), laporan(), dan laporanExport()
     * supaya logika filter hanya ditulis sekali dan hasilnya selalu konsisten.
     */
    private function filteredTransaksi(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        // Filter range tanggal (berdasarkan tanggal pinjam)
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        // Filter status (abaikan kalau "Semua" atau kosong)
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Filter anggota
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        return $query->latest('tanggal_pinjam')->get();
    }

    /**
     * Tampilkan halaman laporan transaksi dengan filter.
     */
    public function laporan(Request $request)
    {
        $transaksis     = $this->filteredTransaksi($request);
        $totalTransaksi = $transaksis->count();
        $totalDenda     = $transaksis->sum('denda');
        $anggotas       = Anggota::orderBy('nama')->get();

        return view('transaksi.laporan', compact('transaksis', 'totalTransaksi', 'totalDenda', 'anggotas'));
    }

    /**
     * Export laporan transaksi (sesuai filter yang sedang aktif) ke PDF.
     */
    public function laporanExport(Request $request)
    {
        $transaksis     = $this->filteredTransaksi($request);
        $totalTransaksi = $transaksis->count();
        $totalDenda     = $transaksis->sum('denda');

        // Info filter yang sedang dipakai, ditampilkan di header PDF
        $filters = [
            'dari'    => $request->dari,
            'sampai'  => $request->sampai,
            'status'  => $request->filled('status') ? $request->status : 'Semua',
            'anggota' => $request->filled('anggota_id')
                ? optional(Anggota::find($request->anggota_id))->nama
                : 'Semua Anggota',
        ];

        $pdf = Pdf::loadView('transaksi.laporan-pdf', compact('transaksis', 'totalTransaksi', 'totalDenda', 'filters'));

        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();
        $bukus    = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('transaksi.create', compact('anggotas', 'bukus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'     => 'required|exists:anggota,id',
            'buku_id'        => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan'     => 'nullable|string',
        ], [
            'anggota_id.required'     => 'Anggota wajib dipilih.',
            'buku_id.required'        => 'Buku wajib dipilih.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $buku = Buku::findOrFail($request->buku_id);

                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
                }

                $kodeTransaksi  = $this->generateKodeTransaksi();
                $tanggalKembali = Carbon::parse($request->tanggal_pinjam)->addDays(7);

                Transaksi::create([
                    'kode_transaksi' => $kodeTransaksi,
                    'anggota_id'     => $request->anggota_id,
                    'buku_id'        => $request->buku_id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => $tanggalKembali,
                    'status'         => 'Dipinjam',
                    'keterangan'     => $request->keterangan,
                ]);

                $buku->decrement('stok');
            });

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi peminjaman berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);

        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Kembalikan buku (update status transaksi).
     */
    public function kembalikan(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status === 'Dikembalikan') {
            return redirect()->route('transaksi.show', $id)
                ->with('error', 'Buku ini sudah pernah dikembalikan.');
        }

        try {
            DB::transaction(function () use ($transaksi) {
                $tanggalDikembalikan = now();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                $transaksi->update([
                    'status'              => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda'               => $denda,
                ]);

                $transaksi->buku->increment('stok');
            });

            return redirect()->route('transaksi.show', $id)
                ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }

    /**
     * Generate kode transaksi otomatis.
     */
    private function generateKodeTransaksi()
    {
        $lastTransaksi = Transaksi::latest()->first();
        $newNumber     = $lastTransaksi ? intval(substr($lastTransaksi->kode_transaksi, -3)) + 1 : 1;

        return 'TRX-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung denda keterlambatan (Rp 5.000 per hari).
     */
    private function hitungDenda($transaksi, $tanggalDikembalikan)
    {
        $hariTerlambat = $transaksi->tanggal_kembali->diffInDays($tanggalDikembalikan, false);

        return $hariTerlambat > 0 ? $hariTerlambat * 5000 : 0;
    }
}
