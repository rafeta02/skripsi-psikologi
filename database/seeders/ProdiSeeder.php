<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\Jenjang;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        // Get IDs for relationships
        $jenjangS1 = Jenjang::where('code', 'S1')->first()->id;
        $jenjangS2 = Jenjang::where('code', 'S2')->first()->id;
        $jenjangS3 = Jenjang::where('code', 'S3')->first()->id;
        
        $fpsi = Faculty::where('code', 'FPSI')->first()->id;
        $fkip = Faculty::where('code', 'FKIP')->first()->id;
        $fe = Faculty::where('code', 'FE')->first()->id;
        $fh = Faculty::where('code', 'FH')->first()->id;
        $ft = Faculty::where('code', 'FT')->first()->id;
        $fmipa = Faculty::where('code', 'FMIPA')->first()->id;

        $prodis = [
            // Fakultas Psikologi
            [
                'code' => 'PSI-S1',
                'name' => 'Psikologi',
                'slug' => 'psikologi-s1',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fpsi,
                'description' => 'Program Studi Psikologi Sarjana',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PSI-S2',
                'name' => 'Magister Psikologi',
                'slug' => 'magister-psikologi',
                'jenjang_id' => $jenjangS2,
                'fakultas_id' => $fpsi,
                'description' => 'Program Studi Magister Psikologi',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PSI-S3',
                'name' => 'Doktor Psikologi',
                'slug' => 'doktor-psikologi',
                'jenjang_id' => $jenjangS3,
                'fakultas_id' => $fpsi,
                'description' => 'Program Studi Doktor Psikologi',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Fakultas Keguruan dan Ilmu Pendidikan
            [
                'code' => 'PGSD',
                'name' => 'Pendidikan Guru Sekolah Dasar',
                'slug' => 'pendidikan-guru-sekolah-dasar',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fkip,
                'description' => 'Program Studi Pendidikan Guru Sekolah Dasar',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PAUD',
                'name' => 'Pendidikan Anak Usia Dini',
                'slug' => 'pendidikan-anak-usia-dini',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fkip,
                'description' => 'Program Studi Pendidikan Anak Usia Dini',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Fakultas Ekonomi
            [
                'code' => 'MANAJEMEN',
                'name' => 'Manajemen',
                'slug' => 'manajemen',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fe,
                'description' => 'Program Studi Manajemen',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'AKUNTANSI',
                'name' => 'Akuntansi',
                'slug' => 'akuntansi',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fe,
                'description' => 'Program Studi Akuntansi',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Fakultas Hukum
            [
                'code' => 'HUKUM',
                'name' => 'Ilmu Hukum',
                'slug' => 'ilmu-hukum',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fh,
                'description' => 'Program Studi Ilmu Hukum',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Fakultas Teknik
            [
                'code' => 'TI',
                'name' => 'Teknik Informatika',
                'slug' => 'teknik-informatika',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $ft,
                'description' => 'Program Studi Teknik Informatika',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SI',
                'name' => 'Sistem Informasi',
                'slug' => 'sistem-informasi',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $ft,
                'description' => 'Program Studi Sistem Informasi',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Fakultas MIPA
            [
                'code' => 'MATEMATIKA',
                'name' => 'Matematika',
                'slug' => 'matematika',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fmipa,
                'description' => 'Program Studi Matematika',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BIOLOGI',
                'name' => 'Biologi',
                'slug' => 'biologi',
                'jenjang_id' => $jenjangS1,
                'fakultas_id' => $fmipa,
                'description' => 'Program Studi Biologi',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Prodi::insert($prodis);
    }
}

