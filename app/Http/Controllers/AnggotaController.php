<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Http\Requests\StoreAnggotaRequest;

use App\Http\Requests\UpdateAnggotaRequest;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotas = Anggota::latest()->get();

        // Statistik
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'Aktif')->count();
        $anggotaNonaktif = Anggota::where('status', 'Nonaktif')->count();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.show', compact('anggota'));
    }

    // Methods lainnya akan diimplementasi di pertemuan 13
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
            // Create anggota baru dengan validated data
            Anggota::create($request->validated());

            // Redirect dengan success message
            return redirect()->route('anggota.index')
                ->with('success', 'Anggota berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan anggota: ' . $e->getMessage());
        }
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

            // Update anggota dengan validated data
            $anggota->update($request->validated());

            // Redirect dengan success message
            return redirect()->route('anggota.show', $anggota->id)
                ->with('success', 'Data anggota berhasil diupdate!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
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

            // Delete anggota
            $anggota->delete();

            // Redirect dengan success message
            return redirect()->route('anggota.index')
                ->with('success', "Anggota '{$namaAnggota}' berhasil dihapus!");
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }

    public function export()
    {
        $anggotas = Anggota::all();
        $filename = 'anggota_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ];

        $callback = function () use ($anggotas) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 supaya Excel baca karakter Indonesia dengan benar
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($file, [
                'Kode',
                'Nama',
                'Email',
                'Telepon',
                'Alamat',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Pekerjaan',
                'Status',
                'Tanggal Daftar'
            ]);

            // Isi data
            foreach ($anggotas as $anggota) {
                fputcsv($file, [
                    $anggota->kode_anggota,
                    $anggota->nama,
                    $anggota->email,
                    // Tambah tab di depan agar Excel tidak ubah jadi angka
                    "\t" . $anggota->telepon,
                    $anggota->alamat,
                    // Format tanggal pakai tanda kutip agar tidak berubah jadi angka
                    $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d-m-Y') : '',
                    $anggota->jenis_kelamin,
                    $anggota->pekerjaan,
                    $anggota->status,
                    $anggota->tanggal_daftar ? $anggota->tanggal_daftar->format('d-m-Y') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function search(Request $request)
    {
        $query = Anggota::query();

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('telepon', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->pekerjaan) {
            $query->where('pekerjaan', $request->pekerjaan);
        }

        $anggotas = $query->latest()->get();

        $totalAnggota = $anggotas->count();
        $anggotaAktif = $anggotas->where('status', 'Aktif')->count();
        $anggotaNonaktif = $anggotas->where('status', 'Nonaktif')->count();

        return view('anggota.index', compact(
            'anggotas',
            'totalAnggota',
            'anggotaAktif',
            'anggotaNonaktif'
        ));
    }

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

        return 'AGT-' . $tahun . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
