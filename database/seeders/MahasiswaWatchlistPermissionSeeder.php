<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MahasiswaWatchlistPermissionSeeder extends Seeder
{
    /**
     * Permission watchlist mahasiswa Reguler (belum daftar sidang).
     *   php artisan db:seed --class=MahasiswaWatchlistPermissionSeeder
     */
    public function run()
    {
        $permissions = [
            159 => 'mahasiswa_watchlist_access',
        ];

        $permissionIds = [];

        foreach ($permissions as $id => $title) {
            $permission = Permission::withTrashed()->where('title', $title)->first();

            if ($permission) {
                if ($permission->trashed()) {
                    $permission->restore();
                }
                $this->command->info("Permission sudah ada: {$title} (id: {$permission->id})");
            } elseif (Permission::withTrashed()->where('id', $id)->exists()) {
                $permission = Permission::create(['title' => $title]);
                $this->command->info("Permission dibuat (id auto): {$title} (id: {$permission->id})");
            } else {
                $permission = Permission::create([
                    'id'    => $id,
                    'title' => $title,
                ]);
                $this->command->info("Permission dibuat: {$title} (id: {$permission->id})");
            }

            $permissionIds[] = $permission->id;
        }

        foreach ([1 => 'Admin', 2 => 'User'] as $roleId => $roleLabel) {
            $role = Role::find($roleId);

            if (! $role) {
                $this->command->warn("Role {$roleLabel} (id: {$roleId}) tidak ditemukan, dilewati.");
                continue;
            }

            $role->permissions()->syncWithoutDetaching($permissionIds);
            $this->command->info("Permission watchlist ditambahkan ke role {$roleLabel}.");
        }

        $this->command->info('Selesai: permission mahasiswa_watchlist_access siap digunakan.');
    }
}
