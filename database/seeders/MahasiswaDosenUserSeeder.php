<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MahasiswaDosenUserSeeder extends Seeder
{
    public function run()
    {
        // Get User role (ID = 2 from RolesTableSeeder)
        $userRole = Role::find(2);

        // Create 5 Mahasiswa Users
        $mahasiswas = Mahasiswa::take(5)->get();
        
        foreach ($mahasiswas as $index => $mahasiswa) {
            $user = User::create([
                'name' => $mahasiswa->nama,
                'email' => strtolower($mahasiswa->nim) . '@student.univ.ac.id',
                'password' => bcrypt('password'),
                'username' => $mahasiswa->nim,
                'no_hp' => '08' . str_pad($index + 1, 10, '0', STR_PAD_LEFT),
                'whatshapp' => '08' . str_pad($index + 1, 10, '0', STR_PAD_LEFT),
                'level' => 'MAHASISWA',
                'identity_number' => $mahasiswa->nim,
                'alamat' => $mahasiswa->alamat,
                'mahasiswa_id' => $mahasiswa->id,
            ]);

            // Attach User role
            if ($userRole) {
                $user->roles()->attach($userRole->id);
            }
        }

        // Create 5 Dosen Users
        $dosens = Dosen::take(5)->get();
        
        foreach ($dosens as $index => $dosen) {
            $user = User::create([
                'name' => $dosen->nama,
                'email' => strtolower($dosen->nip) . '@lecturer.univ.ac.id',
                'password' => bcrypt('password'),
                'username' => $dosen->nip,
                'no_hp' => '08' . str_pad($index + 11, 10, '0', STR_PAD_LEFT),
                'whatshapp' => '08' . str_pad($index + 11, 10, '0', STR_PAD_LEFT),
                'level' => 'DOSEN',
                'identity_number' => $dosen->nip,
                'alamat' => null,
                'dosen_id' => $dosen->id,
            ]);

            // Attach User role
            if ($userRole) {
                $user->roles()->attach($userRole->id);
            }
        }
    }
}

