<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run()
    {
        $faculties = [
            [
                'code' => 'FPSI',
                'name' => 'Fakultas Psikologi',
                'slug' => 'fakultas-psikologi',
                'description' => 'Fakultas Psikologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FKIP',
                'name' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'slug' => 'fakultas-keguruan-dan-ilmu-pendidikan',
                'description' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FE',
                'name' => 'Fakultas Ekonomi',
                'slug' => 'fakultas-ekonomi',
                'description' => 'Fakultas Ekonomi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FH',
                'name' => 'Fakultas Hukum',
                'slug' => 'fakultas-hukum',
                'description' => 'Fakultas Hukum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FT',
                'name' => 'Fakultas Teknik',
                'slug' => 'fakultas-teknik',
                'description' => 'Fakultas Teknik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FMIPA',
                'name' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'slug' => 'fakultas-matematika-dan-ilmu-pengetahuan-alam',
                'description' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FIK',
                'name' => 'Fakultas Ilmu Kesehatan',
                'slug' => 'fakultas-ilmu-kesehatan',
                'description' => 'Fakultas Ilmu Kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FISIP',
                'name' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'slug' => 'fakultas-ilmu-sosial-dan-ilmu-politik',
                'description' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Faculty::insert($faculties);
    }
}

