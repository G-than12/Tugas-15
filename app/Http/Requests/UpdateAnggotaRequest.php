<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // PENTING: nama parameter route untuk resource 'anggota' adalah 'anggotum',
        // bukan 'anggota'. Ini karena Laravel auto-singularize kata "anggota"
        // (diakhiri huruf 'a') dianggap kata Latin seperti "data" -> "datum".
        // Bisa dicek lewat: php artisan route:list --name=anggota
        $anggota = $this->route('anggotum');

        return [
            'kode_anggota' => [
                'required',
                'string',
                'max:20',
                Rule::unique('anggota', 'kode_anggota')->ignore($anggota),
            ],
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('anggota', 'email')->ignore($anggota),
            ],
            'telepon' => [
                'required',
                'regex:/^(\+62|62|0)[0-9]{9,12}$/',
                'min:10',
                'max:15',
            ],
            'alamat' => 'required|string',
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pekerjaan' => 'nullable|string|max:50',
            'tanggal_daftar' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'status' => 'required|in:Aktif,Nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_anggota.unique' => 'Kode anggota sudah digunakan.',
            'email.unique' => 'Email sudah terdaftar.',
            'telepon.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_daftar.before_or_equal' => 'Tanggal pendaftaran tidak boleh di masa depan.',
        ];
    }
}
