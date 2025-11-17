<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;

class ArticleCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Berita Kampus',
                'slug' => 'berita-kampus',
                'description' => 'Berita dan informasi terkini seputar kampus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penelitian',
                'slug' => 'penelitian',
                'description' => 'Artikel tentang penelitian dan publikasi ilmiah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pengabdian Masyarakat',
                'slug' => 'pengabdian-masyarakat',
                'description' => 'Kegiatan pengabdian kepada masyarakat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Prestasi Mahasiswa',
                'slug' => 'prestasi-mahasiswa',
                'description' => 'Pencapaian dan prestasi mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Akademik',
                'slug' => 'akademik',
                'description' => 'Informasi akademik dan perkuliahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kegiatan Mahasiswa',
                'slug' => 'kegiatan-mahasiswa',
                'description' => 'Kegiatan dan organisasi kemahasiswaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seminar dan Workshop',
                'slug' => 'seminar-dan-workshop',
                'description' => 'Informasi seminar, workshop, dan pelatihan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi kampus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ArticleCategory::insert($categories);
    }
}

