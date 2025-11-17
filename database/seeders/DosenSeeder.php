<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\ResearchGroup;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $dosens = [
            [
                'nip' => '197501012000031001',
                'nidn' => '0101017501',
                'nama' => 'Dr. Ahmad Wijaya, M.Psi.',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '01-01-1975',
                'gender' => 'male',
            ],
            [
                'nip' => '198003152005012001',
                'nidn' => '0115038001',
                'nama' => 'Dr. Siti Nurhaliza, M.Psi., Psikolog',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '15-03-1980',
                'gender' => 'female',
            ],
            [
                'nip' => '198206202008011002',
                'nidn' => '0120068201',
                'nama' => 'Prof. Dr. Budi Santoso, M.Si.',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '20-06-1982',
                'gender' => 'male',
            ],
            [
                'nip' => '198509102010012003',
                'nidn' => '0110098501',
                'nama' => 'Dr. Rina Kusuma, M.Psi., Psikolog',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '10-09-1985',
                'gender' => 'female',
            ],
            [
                'nip' => '197812252003121001',
                'nidn' => '0125127801',
                'nama' => 'Dr. Hendra Saputra, M.Psi.',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '25-12-1978',
                'gender' => 'male',
            ],
            [
                'nip' => '198707152012012004',
                'nidn' => '0115078701',
                'nama' => 'Dra. Dewi Lestari, M.Psi., Psikolog',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '15-07-1987',
                'gender' => 'female',
            ],
            [
                'nip' => '197904082002121002',
                'nidn' => '0108047901',
                'nama' => 'Dr. Agus Priyanto, M.Si., Psikolog',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '08-04-1979',
                'gender' => 'male',
            ],
            [
                'nip' => '198811202014012005',
                'nidn' => '0120118801',
                'nama' => 'Dr. Maya Sari, M.Psi.',
                'tempat_lahir' => 'Solo',
                'tanggal_lahir' => '20-11-1988',
                'gender' => 'female',
            ],
            [
                'nip' => '198102182006041003',
                'nidn' => '0118028101',
                'nama' => 'Dr. Rudi Hermawan, M.Psi., Psikolog',
                'tempat_lahir' => 'Bekasi',
                'tanggal_lahir' => '18-02-1981',
                'gender' => 'male',
            ],
            [
                'nip' => '198605302011012006',
                'nidn' => '0130058601',
                'nama' => 'Dra. Fitri Rahmawati, M.Psi.',
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '30-05-1986',
                'gender' => 'female',
            ],
        ];

        // Get first available faculty, jenjang, prodi, and research group
        $fakultas = Faculty::first();
        $jenjang = Jenjang::first();
        $prodi = Prodi::first();
        $researchGroup = ResearchGroup::first();

        foreach ($dosens as $dosen) {
            Dosen::create(array_merge($dosen, [
                'fakultas_id' => $fakultas ? $fakultas->id : null,
                'jenjang_id' => $jenjang ? $jenjang->id : null,
                'prodi_id' => $prodi ? $prodi->id : null,
                'riset_grup_id' => $researchGroup ? $researchGroup->id : null,
            ]));
        }
    }
}

