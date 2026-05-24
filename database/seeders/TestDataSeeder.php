<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Faculty;
use App\Models\Keilmuan;
use App\Models\ResearchGroup;
use App\Models\Ruang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds for testing environment.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating test data...');

        // Create Roles if not exists
        $roles = ['Administrator', 'Dosen', 'Mahasiswa'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Create Faculty
        $faculty = Faculty::firstOrCreate(
            ['name' => 'Fakultas Psikologi'],
            ['code' => 'PSI']
        );

        // Create Prodi
        $prodi = Prodi::firstOrCreate(
            ['name' => 'S1 Psikologi'],
            [
                'code' => 'PSI-S1',
                'faculty_id' => $faculty->id
            ]
        );

        // Create Keilmuan
        $keilmuan1 = Keilmuan::firstOrCreate(
            ['name' => 'Psikologi Klinis'],
            ['code' => 'KLINIS']
        );

        $keilmuan2 = Keilmuan::firstOrCreate(
            ['name' => 'Psikologi Pendidikan'],
            ['code' => 'PENDIDIKAN']
        );

        $keilmuan3 = Keilmuan::firstOrCreate(
            ['name' => 'Psikologi Sosial'],
            ['code' => 'SOSIAL']
        );

        // Create Research Groups
        $rg1 = ResearchGroup::firstOrCreate(
            ['name' => 'Penelitian Kesehatan Mental'],
            [
                'code' => 'RG-MENTAL',
                'keilmuan_id' => $keilmuan1->id
            ]
        );

        $rg2 = ResearchGroup::firstOrCreate(
            ['name' => 'Penelitian Pendidikan dan Pembelajaran'],
            [
                'code' => 'RG-EDU',
                'keilmuan_id' => $keilmuan2->id
            ]
        );

        $rg3 = ResearchGroup::firstOrCreate(
            ['name' => 'Penelitian Dinamika Sosial'],
            [
                'code' => 'RG-SOCIAL',
                'keilmuan_id' => $keilmuan3->id
            ]
        );

        // Create Ruang (Rooms)
        $ruang1 = Ruang::firstOrCreate(
            ['name' => 'Ruang Seminar 1'],
            ['code' => 'R-SEM-1', 'capacity' => 50]
        );

        $ruang2 = Ruang::firstOrCreate(
            ['name' => 'Ruang Seminar 2'],
            ['code' => 'R-SEM-2', 'capacity' => 30]
        );

        $ruang3 = Ruang::firstOrCreate(
            ['name' => 'Ruang Sidang'],
            ['code' => 'R-SIDANG', 'capacity' => 20]
        );

        // ========================================
        // CREATE ADMIN USER
        // ========================================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrator Test',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole('Administrator');
        $this->command->info('✓ Admin user created: admin@test.com / password');

        // ========================================
        // CREATE DOSEN USERS (3)
        // ========================================
        $dosenData = [
            [
                'email' => 'dosen1@test.com',
                'name' => 'Dr. Ahmad Wijaya, M.Psi',
                'nip' => '198501012010011001',
                'nidn' => '0101018501',
                'keilmuan_id' => $keilmuan1->id,
            ],
            [
                'email' => 'dosen2@test.com',
                'name' => 'Dr. Siti Nurhaliza, M.Psi',
                'nip' => '198702152011012001',
                'nidn' => '0215028701',
                'keilmuan_id' => $keilmuan2->id,
            ],
            [
                'email' => 'dosen3@test.com',
                'name' => 'Prof. Budi Santoso, Ph.D',
                'nip' => '198303202012011001',
                'nidn' => '0320038301',
                'keilmuan_id' => $keilmuan3->id,
            ],
        ];

        foreach ($dosenData as $index => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            $dosen = Dosen::firstOrCreate(
                ['nip' => $data['nip']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['name'],
                    'nidn' => $data['nidn'],
                    'keilmuan_id' => $data['keilmuan_id'],
                    'jabatan' => $index === 2 ? 'Profesor' : 'Lektor',
                ]
            );

            $user->dosen_id = $dosen->id;
            $user->save();
            $user->assignRole('Dosen');

            $this->command->info("✓ Dosen user created: {$data['email']} / password");
        }

        // ========================================
        // CREATE MAHASISWA USERS (3)
        // ========================================
        $mahasiswaData = [
            [
                'email' => 'mahasiswa1@test.com',
                'name' => 'Andi Pratama',
                'nim' => '2019010001',
                'phone' => '081234567001',
            ],
            [
                'email' => 'mahasiswa2@test.com',
                'name' => 'Dewi Lestari',
                'nim' => '2019010002',
                'phone' => '081234567002',
            ],
            [
                'email' => 'mahasiswa3@test.com',
                'name' => 'Candra Kusuma',
                'nim' => '2019010003',
                'phone' => '081234567003',
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            $mahasiswa = Mahasiswa::firstOrCreate(
                ['nim' => $data['nim']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['name'],
                    'prodi_id' => $prodi->id,
                    'angkatan' => '2019',
                    'no_hp' => $data['phone'],
                    'alamat' => 'Jl. Test No. ' . substr($data['nim'], -2),
                ]
            );

            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();
            $user->assignRole('Mahasiswa');

            $this->command->info("✓ Mahasiswa user created: {$data['email']} / password");
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('TEST DATA SEEDING COMPLETE!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Admin Account:');
        $this->command->info('  Email: admin@test.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Dosen Accounts (1-3):');
        $this->command->info('  Email: dosen1@test.com - dosen3@test.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Mahasiswa Accounts (1-3):');
        $this->command->info('  Email: mahasiswa1@test.com - mahasiswa3@test.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Master Data:');
        $this->command->info('  - Faculty: Fakultas Psikologi');
        $this->command->info('  - Prodi: S1 Psikologi');
        $this->command->info('  - Keilmuan: 3 (Klinis, Pendidikan, Sosial)');
        $this->command->info('  - Research Groups: 3');
        $this->command->info('  - Ruang: 3 (Seminar 1, Seminar 2, Sidang)');
        $this->command->info('========================================');
    }
}
