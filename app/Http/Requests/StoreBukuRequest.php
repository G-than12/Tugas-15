<?php

namespace App\Http\Requests;

use App\Rules\KodeBukuFormat;
use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    /**
     * Mapping kategori -> prefix kode buku.
     */
    private array $prefixKategori = [
        'Programming'  => 'PROG',
        'Database'     => 'DB',
        'Web Design'   => 'WEB',
        'Networking'   => 'NET',
        'Data Science' => 'DS',
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Jalan otomatis sebelum rules() divalidasi.
     * Kode buku selalu di-generate ulang di server berdasarkan kategori,
     * mengabaikan input apapun yang dikirim dari form (field-nya readonly di UI).
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('kategori') && array_key_exists($this->kategori, $this->prefixKategori)) {
            $this->merge([
                'kode_buku' => $this->generateKodeBuku($this->kategori),
            ]);
        }
    }

    /**
     * Generate kode buku unik berdasarkan kategori, format: BK-{PREFIX}-{NOMOR}
     * Nomor urut dihitung dari kode terakhir yang sudah ada untuk kategori tsb.
     */
    private function generateKodeBuku(string $kategori): string
    {
        $prefix = $this->prefixKategori[$kategori];

        $nomorTerakhir = \App\Models\Buku::where('kategori', $kategori)
            ->where('kode_buku', 'like', "BK-{$prefix}-%")
            ->pluck('kode_buku')
            ->map(function ($kode) {
                // ambil angka di akhir kode, misal "BK-PROG-007" -> 7
                return (int) substr($kode, strrpos($kode, '-') + 1);
            })
            ->max();

        $nomorBaru = ($nomorTerakhir ?? 0) + 1;

        return sprintf('BK-%s-%03d', $prefix, $nomorBaru);
    }

    public function rules(): array
    {
        return [
            'kode_buku'    => ['required', 'string', 'max:20', 'unique:buku,kode_buku', new KodeBukuFormat],
            'judul'        => 'required|string|max:200',
            'kategori'     => 'required|in:Programming,Database,Web Design,Networking,Data Science',
            'pengarang'    => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20',
            'harga'        => 'required|numeric|min:0',
            'stok'         => ['required', 'integer', 'min:0', $this->getStokMaxRule()],
            'deskripsi'    => 'nullable|string',
            'bahasa'       => ['required', 'string', 'max:20', $this->getBahasaRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_buku.required'    => 'Kode buku wajib diisi.',
            'kode_buku.unique'      => 'Kode buku sudah digunakan.',
            'kode_buku.max'         => 'Kode buku maksimal 20 karakter.',

            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.max'             => 'Judul buku maksimal 200 karakter.',

            'kategori.required'     => 'Kategori wajib dipilih.',
            'kategori.in'           => 'Kategori tidak valid.',

            'pengarang.required'    => 'Nama pengarang wajib diisi.',
            'penerbit.required'     => 'Nama penerbit wajib diisi.',

            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min'      => 'Tahun terbit tidak valid.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh melebihi tahun sekarang.',

            'isbn.max'              => 'ISBN maksimal 20 karakter.',

            'harga.required'        => 'Harga buku wajib diisi.',
            'harga.numeric'         => 'Harga harus berupa angka.',
            'harga.min'             => 'Harga tidak boleh negatif.',

            'stok.required'         => 'Stok wajib diisi.',
            'stok.integer'          => 'Stok harus berupa angka bulat.',
            'stok.min'              => 'Stok tidak boleh negatif.',
            'stok.max'              => 'Buku terbitan sebelum tahun 2000 stoknya maksimal 5.',

            'bahasa.required'       => 'Bahasa wajib diisi.',
            'bahasa.in'             => 'Buku kategori Programming wajib berbahasa Inggris.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_buku'    => 'kode buku',
            'judul'        => 'judul buku',
            'kategori'     => 'kategori',
            'pengarang'    => 'nama pengarang',
            'penerbit'     => 'nama penerbit',
            'tahun_terbit' => 'tahun terbit',
            'isbn'         => 'ISBN',
            'harga'        => 'harga',
            'stok'         => 'stok',
            'bahasa'       => 'bahasa',
        ];
    }

    /**
     * Stok maksimal 5 untuk buku terbitan sebelum tahun 2000,
     * selain itu maksimal 99999.
     */
    private function getStokMaxRule(): string
    {
        if ($this->tahun_terbit && (int) $this->tahun_terbit < 2000) {
            return 'max:5';
        }

        return 'max:99999';
    }

    /**
     * Buku kategori Programming wajib berbahasa Inggris.
     * Kategori lain boleh Indonesia atau Inggris.
     */
    private function getBahasaRule(): string
    {
        if ($this->kategori === 'Programming') {
            return 'in:Inggris';
        }

        return 'in:Indonesia,Inggris';
    }
}
