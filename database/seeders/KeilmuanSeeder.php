<?php

namespace Database\Seeders;

use App\Models\Keilmuan;
use Illuminate\Database\Seeder;

class KeilmuanSeeder extends Seeder
{
    public function run()
    {
        $keilmuans = [
            [
                'name' => 'Psikologi Klinis',
                'slug' => 'psikologi-klinis',
                'description' => 'Bidang keilmuan yang fokus pada diagnosis, assessment, dan intervensi terhadap gangguan psikologis dan perilaku',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Pendidikan',
                'slug' => 'psikologi-pendidikan',
                'description' => 'Bidang keilmuan yang mempelajari proses belajar mengajar, perkembangan kognitif, dan metode pembelajaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Industri dan Organisasi',
                'slug' => 'psikologi-industri-dan-organisasi',
                'description' => 'Bidang keilmuan yang fokus pada perilaku manusia dalam konteks kerja dan organisasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Sosial',
                'slug' => 'psikologi-sosial',
                'description' => 'Bidang keilmuan yang mempelajari bagaimana pikiran, perasaan, dan perilaku individu dipengaruhi oleh kehadiran orang lain',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Perkembangan',
                'slug' => 'psikologi-perkembangan',
                'description' => 'Bidang keilmuan yang mempelajari perubahan psikologis sepanjang rentang kehidupan manusia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Kognitif',
                'slug' => 'psikologi-kognitif',
                'description' => 'Bidang keilmuan yang mempelajari proses mental seperti persepsi, memori, pemikiran, dan pemecahan masalah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Neuropsikologi',
                'slug' => 'neuropsikologi',
                'description' => 'Bidang keilmuan yang mempelajari hubungan antara otak dan perilaku serta fungsi kognitif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Kesehatan',
                'slug' => 'psikologi-kesehatan',
                'description' => 'Bidang keilmuan yang fokus pada bagaimana faktor biologis, psikologis, dan sosial mempengaruhi kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Komunitas',
                'slug' => 'psikologi-komunitas',
                'description' => 'Bidang keilmuan yang fokus pada hubungan antara individu dan komunitas serta pemberdayaan masyarakat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikometri',
                'slug' => 'psikometri',
                'description' => 'Bidang keilmuan yang fokus pada pengukuran psikologis, pengembangan tes, dan analisis data psikologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Forensik',
                'slug' => 'psikologi-forensik',
                'description' => 'Bidang keilmuan yang mengaplikasikan prinsip psikologi dalam konteks hukum dan peradilan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Positif',
                'slug' => 'psikologi-positif',
                'description' => 'Bidang keilmuan yang fokus pada kekuatan karakter, keberfungsian optimal, dan kesejahteraan psikologis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Eksperimental',
                'slug' => 'psikologi-eksperimental',
                'description' => 'Bidang keilmuan yang menggunakan metode eksperimental untuk mempelajari perilaku dan proses mental',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Abnormal',
                'slug' => 'psikologi-abnormal',
                'description' => 'Bidang keilmuan yang mempelajari pola perilaku, emosi, dan pikiran yang tidak biasa atau maladaptif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psikologi Konseling',
                'slug' => 'psikologi-konseling',
                'description' => 'Bidang keilmuan yang fokus pada membantu individu mengatasi masalah personal dan mengembangkan potensi diri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Keilmuan::insert($keilmuans);
    }
}

