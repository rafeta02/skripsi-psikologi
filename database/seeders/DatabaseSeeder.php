<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            
            // Master Data Seeders
            JenjangSeeder::class,
            FacultySeeder::class,
            ProdiSeeder::class,
            KeilmuanSeeder::class,
            ResearchGroupSeeder::class,
            RuangSeeder::class,
            
            // User Data Seeders
            DosenSeeder::class,
            MahasiswaSeeder::class,
            MahasiswaDosenUserSeeder::class,
            
            // Blog Seeders
            ArticleCategorySeeder::class,
            ArticleTagSeeder::class,
            
        ]);
    }
}
