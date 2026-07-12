<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MonitoringMenusPermissionSeeder extends Seeder
{
    /**
     * Tambahkan permission menu monitoring baru dan assign ke role Admin & User.
     * Aman dijalankan ulang pada database yang sudah ada:
     *   php artisan db:seed --class=MonitoringMenusPermissionSeeder
     */
    public function run()
    {
        $permissions = [
            150 => 'dosen_workload_pembimbing_access',
            151 => 'dosen_workload_penguji_access',
            152 => 'final_score_recap_access',
            153 => 'thesis_title_database_access',
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

            if (!$role) {
                $this->command->warn("Role {$roleLabel} (id: {$roleId}) tidak ditemukan, dilewati.");
                continue;
            }

            $role->permissions()->syncWithoutDetaching($permissionIds);
            $this->command->info("Permission monitoring menus ditambahkan ke role {$roleLabel}.");
        }

        $this->command->info('Selesai: permission rekap pembimbing/penguji, nilai akhir, dan database judul siap digunakan.');
    }
}
