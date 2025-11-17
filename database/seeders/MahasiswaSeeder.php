<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Faculty;
use App\Models\Jenjang;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $mahasiswas = [
            [
                'nim' => '2021010001',
                'nama' => 'Andi Pratama',
                'tahun_masuk' => '2021',
                'kelas' => 'A',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '15-01-2003',
                'gender' => 'male',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            ],
            [
                'nim' => '2021010002',
                'nama' => 'Siti Aminah',
                'tahun_masuk' => '2021',
                'kelas' => 'A',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '20-03-2003',
                'gender' => 'female',
                'alamat' => 'Jl. Dago No. 45, Bandung',
            ],
            [
                'nim' => '2021010003',
                'nama' => 'Budi Santoso',
                'tahun_masuk' => '2021',
                'kelas' => 'A',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '10-11-2002',
                'gender' => 'male',
                'alamat' => 'Jl. Tunjungan No. 78, Surabaya',
            ],
            [
                'nim' => '2021010004',
                'nama' => 'Dewi Lestari',
                'tahun_masuk' => '2021',
                'kelas' => 'B',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '25-05-2003',
                'gender' => 'female',
                'alamat' => 'Jl. Malioboro No. 12, Yogyakarta',
            ],
            [
                'nim' => '2021010005',
                'nama' => 'Eko Prasetyo',
                'tahun_masuk' => '2021',
                'kelas' => 'B',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '08-07-2003',
                'gender' => 'male',
                'alamat' => 'Jl. Pandanaran No. 56, Semarang',
            ],
            [
                'nim' => '2022010001',
                'nama' => 'Fitri Handayani',
                'tahun_masuk' => '2022',
                'kelas' => 'A',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '14-02-2004',
                'gender' => 'female',
                'alamat' => 'Jl. Gatot Subroto No. 89, Medan',
            ],
            [
                'nim' => '2022010002',
                'nama' => 'Gunawan Wibisono',
                'tahun_masuk' => '2022',
                'kelas' => 'A',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '30-04-2004',
                'gender' => 'male',
                'alamat' => 'Jl. Ijen No. 34, Malang',
            ],
            [
                'nim' => '2022010003',
                'nama' => 'Hana Pertiwi',
                'tahun_masuk' => '2022',
                'kelas' => 'B',
                'tempat_lahir' => 'Solo',
                'tanggal_lahir' => '18-06-2004',
                'gender' => 'female',
                'alamat' => 'Jl. Slamet Riyadi No. 67, Solo',
            ],
            [
                'nim' => '2022010004',
                'nama' => 'Indra Kusuma',
                'tahun_masuk' => '2022',
                'kelas' => 'B',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '22-08-2004',
                'gender' => 'male',
                'alamat' => 'Jl. Pettarani No. 23, Makassar',
            ],
            [
                'nim' => '2022010005',
                'nama' => 'Julia Rahmawati',
                'tahun_masuk' => '2022',
                'kelas' => 'C',
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '12-09-2004',
                'gender' => 'female',
                'alamat' => 'Jl. Sudirman No. 101, Palembang',
            ],
            [
                'nim' => '2023010001',
                'nama' => 'Kevin Anggara',
                'tahun_masuk' => '2023',
                'kelas' => 'A',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '05-01-2005',
                'gender' => 'male',
                'alamat' => 'Jl. Teuku Umar No. 45, Denpasar',
            ],
            [
                'nim' => '2023010002',
                'nama' => 'Lisa Marlina',
                'tahun_masuk' => '2023',
                'kelas' => 'A',
                'tempat_lahir' => 'Pontianak',
                'tanggal_lahir' => '17-03-2005',
                'gender' => 'female',
                'alamat' => 'Jl. Ahmad Yani No. 78, Pontianak',
            ],
            [
                'nim' => '2023010003',
                'nama' => 'Muhammad Rizki',
                'tahun_masuk' => '2023',
                'kelas' => 'A',
                'tempat_lahir' => 'Pekanbaru',
                'tanggal_lahir' => '28-05-2005',
                'gender' => 'male',
                'alamat' => 'Jl. Sudirman No. 234, Pekanbaru',
            ],
            [
                'nim' => '2023010004',
                'nama' => 'Nanda Putri',
                'tahun_masuk' => '2023',
                'kelas' => 'B',
                'tempat_lahir' => 'Samarinda',
                'tanggal_lahir' => '09-07-2005',
                'gender' => 'female',
                'alamat' => 'Jl. Pramuka No. 56, Samarinda',
            ],
            [
                'nim' => '2023010005',
                'nama' => 'Oscar Pratama',
                'tahun_masuk' => '2023',
                'kelas' => 'B',
                'tempat_lahir' => 'Balikpapan',
                'tanggal_lahir' => '21-09-2005',
                'gender' => 'male',
                'alamat' => 'Jl. Jenderal Sudirman No. 89, Balikpapan',
            ],
        ];

        // Get first available faculty, jenjang, and prodi
        $fakultas = Faculty::first();
        $jenjang = Jenjang::first();
        $prodi = Prodi::first();

        foreach ($mahasiswas as $mahasiswa) {
            Mahasiswa::create(array_merge($mahasiswa, [
                'fakultas_id' => $fakultas ? $fakultas->id : null,
                'jenjang_id' => $jenjang ? $jenjang->id : null,
                'prodi_id' => $prodi ? $prodi->id : null,
            ]));
        }
    }
}

