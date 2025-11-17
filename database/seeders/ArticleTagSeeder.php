<?php

namespace Database\Seeders;

use App\Models\ArticleTag;
use Illuminate\Database\Seeder;

class ArticleTagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            [
                'name' => 'Psikologi',
                'slug' => 'psikologi',
                'description' => 'Tag untuk konten psikologi',
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Skripsi',
                'slug' => 'skripsi',
                'description' => 'Tag untuk konten skripsi',
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MBKM',
                'slug' => 'mbkm',
                'description' => 'Tag untuk konten MBKM',
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penelitian',
                'slug' => 'penelitian',
                'description' => 'Tag untuk konten penelitian',
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Tag untuk konten pendidikan',
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahasiswa',
                'slug' => 'mahasiswa',
                'description' => 'Tag untuk konten mahasiswa',
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dosen',
                'slug' => 'dosen',
                'description' => 'Tag untuk konten dosen',
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seminar',
                'slug' => 'seminar',
                'description' => 'Tag untuk konten seminar',
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Workshop',
                'slug' => 'workshop',
                'description' => 'Tag untuk konten workshop',
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wisuda',
                'slug' => 'wisuda',
                'description' => 'Tag untuk konten wisuda',
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beasiswa',
                'slug' => 'beasiswa',
                'description' => 'Tag untuk konten beasiswa',
                'featured' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kompetisi',
                'slug' => 'kompetisi',
                'description' => 'Tag untuk konten kompetisi',
                'featured' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ArticleTag::insert($tags);
    }
}

