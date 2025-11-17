<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    public function run()
    {
        $ruangs = [
            [
                'code' => 'R101',
                'name' => 'Ruang Kuliah 101',
                'slug' => 'ruang-kuliah-101',
                'location' => 'Gedung A Lantai 1',
                'capacity' => 40,
                'facility' => 'Proyektor, AC, Whiteboard, Sound System',
                'description' => 'Ruang kuliah standar dengan kapasitas 40 mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'R102',
                'name' => 'Ruang Kuliah 102',
                'slug' => 'ruang-kuliah-102',
                'location' => 'Gedung A Lantai 1',
                'capacity' => 40,
                'facility' => 'Proyektor, AC, Whiteboard',
                'description' => 'Ruang kuliah standar dengan kapasitas 40 mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'R201',
                'name' => 'Ruang Kuliah 201',
                'slug' => 'ruang-kuliah-201',
                'location' => 'Gedung A Lantai 2',
                'capacity' => 50,
                'facility' => 'Proyektor, AC, Whiteboard, Sound System, Smart TV',
                'description' => 'Ruang kuliah besar dengan kapasitas 50 mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'R202',
                'name' => 'Ruang Kuliah 202',
                'slug' => 'ruang-kuliah-202',
                'location' => 'Gedung A Lantai 2',
                'capacity' => 50,
                'facility' => 'Proyektor, AC, Whiteboard, Sound System',
                'description' => 'Ruang kuliah besar dengan kapasitas 50 mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LAB-PSI-1',
                'name' => 'Laboratorium Psikologi 1',
                'slug' => 'laboratorium-psikologi-1',
                'location' => 'Gedung Psikologi Lantai 2',
                'capacity' => 30,
                'facility' => 'Komputer, AC, One-way Mirror, Recording Equipment',
                'description' => 'Laboratorium psikologi untuk praktikum dan penelitian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LAB-PSI-2',
                'name' => 'Laboratorium Psikologi 2',
                'slug' => 'laboratorium-psikologi-2',
                'location' => 'Gedung Psikologi Lantai 2',
                'capacity' => 25,
                'facility' => 'Alat tes psikologi, AC, Meja konseling',
                'description' => 'Laboratorium psikologi untuk praktikum assessment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'AULA',
                'name' => 'Aula Utama',
                'slug' => 'aula-utama',
                'location' => 'Gedung Rektorat Lantai 3',
                'capacity' => 200,
                'facility' => 'Proyektor, AC, Sound System, Wireless Mic, Panggung, Kursi Lipat',
                'description' => 'Aula besar untuk seminar dan acara kampus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SEMINAR-1',
                'name' => 'Ruang Seminar 1',
                'slug' => 'ruang-seminar-1',
                'location' => 'Gedung B Lantai 3',
                'capacity' => 80,
                'facility' => 'Proyektor, AC, Sound System, Whiteboard, Wireless Mic',
                'description' => 'Ruang seminar untuk acara akademik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SIDANG-1',
                'name' => 'Ruang Sidang 1',
                'slug' => 'ruang-sidang-1',
                'location' => 'Gedung Psikologi Lantai 3',
                'capacity' => 15,
                'facility' => 'Proyektor, AC, Meja Sidang, Recording Equipment',
                'description' => 'Ruang sidang untuk ujian skripsi dan tesis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SIDANG-2',
                'name' => 'Ruang Sidang 2',
                'slug' => 'ruang-sidang-2',
                'location' => 'Gedung Psikologi Lantai 3',
                'capacity' => 15,
                'facility' => 'Proyektor, AC, Meja Sidang',
                'description' => 'Ruang sidang untuk ujian skripsi dan tesis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'R-DISKUSI-1',
                'name' => 'Ruang Diskusi 1',
                'slug' => 'ruang-diskusi-1',
                'location' => 'Gedung A Lantai 1',
                'capacity' => 20,
                'facility' => 'Whiteboard, AC, Meja Bundar',
                'description' => 'Ruang diskusi untuk kerja kelompok',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'R-DISKUSI-2',
                'name' => 'Ruang Diskusi 2',
                'slug' => 'ruang-diskusi-2',
                'location' => 'Gedung A Lantai 1',
                'capacity' => 20,
                'facility' => 'Whiteboard, AC, Meja Bundar',
                'description' => 'Ruang diskusi untuk kerja kelompok',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Ruang::insert($ruangs);
    }
}

