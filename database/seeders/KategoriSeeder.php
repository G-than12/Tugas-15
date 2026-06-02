<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = [
            [
                'nama_kategori' => 'Programming',
                'deskripsi'     => 'Buku-buku tentang pemrograman dan pengembangan software',
                'icon'          => 'code-slash',
                'warna'         => 'primary',
            ],
            [
                'nama_kategori' => 'Database',
                'deskripsi'     => 'Buku-buku tentang manajemen dan administrasi database',
                'icon'          => 'database',
                'warna'         => 'success',
            ],
            [
                'nama_kategori' => 'Web Design',
                'deskripsi'     => 'Buku-buku tentang desain dan tampilan antarmuka web',
                'icon'          => 'palette',
                'warna'         => 'info',
            ],
            [
                'nama_kategori' => 'Networking',
                'deskripsi'     => 'Buku-buku tentang jaringan komputer dan infrastruktur',
                'icon'          => 'wifi',
                'warna'         => 'warning',
            ],
            [
                'nama_kategori' => 'Data Science',
                'deskripsi'     => 'Buku-buku tentang ilmu data, machine learning, dan analitik',
                'icon'          => 'graph-up',
                'warna'         => 'danger',
            ],
        ];

        foreach ($kategoriList as $item) {
            Kategori::create($item);
        }
    }
}