<?php

namespace App\Http\Requests;

use App\Models\Buku;
use App\Rules\KodeBukuFormat;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Jalan otomatis sebelum rules() divalidasi.
     *
     * kode_buku dan kategori TIDAK BOLEH diubah lewat form edit, karena
     * kode_buku itu di-generate berdasarkan kategori saat buku dibuat
     * (lihat StoreBukuRequest::generateKodeBuku()). Kalau kategori atau
     * kode_buku diubah lewat edit tanpa kontrol, hubungan prefix kode
     * dengan kategori jadi tidak konsisten lagi (mis. kategori "Database"
     * tapi kode_buku masih "BK-PROG-007").
     *
     * Maka di sini kita selalu timpa kembali kedua field tsb dengan nilai
     * asli dari database, apapun yang dikirim dari form/request — sama
     * seperti prinsip "field readonly di UI, dipaksa ulang di server"
     * yang dipakai di StoreBukuRequest.
     */
    protected function prepareForValidation(): void
    {
        $buku = Buku::find($this->route('buku'));

        if ($buku) {
            $this->merge([
                'kategori'  => $buku->kategori,
                'kode_buku' => $buku->kode_buku,
            ]);
        }
    }

    public function rules(): array
    {
        // Ambil ID buku dari URL (misal: /buku/5/edit → ambil angka 5)
        $bukuId = $this->route('buku');

        return [
            'kode_buku'    => ['required', 'string', 'max:20', 'unique:buku,kode_buku,' . $bukuId, new KodeBukuFormat],
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

    private function getStokMaxRule(): string
    {
        if ($this->tahun_terbit && (int) $this->tahun_terbit < 2000) {
            return 'max:5';
        }

        return 'max:99999';
    }

    private function getBahasaRule(): string
    {
        if ($this->kategori === 'Programming') {
            return 'in:Inggris';
        }

        return 'in:Indonesia,Inggris';
    }
}
