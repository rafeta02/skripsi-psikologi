<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use Illuminate\Database\Seeder;

class JenjangSeeder extends Seeder
{
    public function run()
    {
        $jenjangs = [
            [
                'code' => 'D3',
                'name' => 'Diploma 3',
                'slug' => 'diploma-3',
                'description' => 'Program pendidikan vokasi tingkat Diploma 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'D4',
                'name' => 'Diploma 4',
                'slug' => 'diploma-4',
                'description' => 'Program pendidikan vokasi tingkat Diploma 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S1',
                'name' => 'Sarjana',
                'slug' => 'sarjana',
                'description' => 'Program pendidikan tingkat Sarjana (Strata 1)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S2',
                'name' => 'Magister',
                'slug' => 'magister',
                'description' => 'Program pendidikan tingkat Magister (Strata 2)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'S3',
                'name' => 'Doktor',
                'slug' => 'doktor',
                'description' => 'Program pendidikan tingkat Doktor (Strata 3)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PROF',
                'name' => 'Profesi',
                'slug' => 'profesi',
                'description' => 'Program pendidikan profesi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Jenjang::insert($jenjangs);
    }
}

