<?php

namespace Database\Seeders;

use App\Models\ResearchGroup;
use Illuminate\Database\Seeder;

class ResearchGroupSeeder extends Seeder
{
    public function run()
    {
        $researchGroups = [
            [
                'name' => 'Psikologi Klinis',
                'slug' => 'psikologi-klinis',
                'description' => 'Kelompok riset yang fokus pada psikologi klinis dan psikopatologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Pendidikan',
                'slug' => 'psikologi-pendidikan',
                'description' => 'Kelompok riset yang fokus pada psikologi pendidikan dan pembelajaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Industri dan Organisasi',
                'slug' => 'psikologi-industri-dan-organisasi',
                'description' => 'Kelompok riset yang fokus pada psikologi industri dan organisasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Sosial',
                'slug' => 'psikologi-sosial',
                'description' => 'Kelompok riset yang fokus pada psikologi sosial dan komunitas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Perkembangan',
                'slug' => 'psikologi-perkembangan',
                'description' => 'Kelompok riset yang fokus pada psikologi perkembangan sepanjang rentang kehidupan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Kognitif',
                'slug' => 'psikologi-kognitif',
                'description' => 'Kelompok riset yang fokus pada psikologi kognitif dan neuropsikologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikometri dan Assessment',
                'slug' => 'psikometri-dan-assessment',
                'description' => 'Kelompok riset yang fokus pada psikometri dan pengembangan alat ukur psikologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Kesehatan',
                'slug' => 'psikologi-kesehatan',
                'description' => 'Kelompok riset yang fokus pada psikologi kesehatan dan kesejahteraan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        ResearchGroup::insert($researchGroups);
    }
}

