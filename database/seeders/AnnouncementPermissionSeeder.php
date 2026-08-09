<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AnnouncementPermissionSeeder extends Seeder
{
    /**
     * Tambahkan permission Pengumuman dan assign ke role Admin & User.
     *   php artisan db:seed --class=AnnouncementPermissionSeeder
     */
    public function run()
    {
        $permissions = [
            154 => 'announcement_create',
            155 => 'announcement_edit',
            156 => 'announcement_show',
            157 => 'announcement_delete',
            158 => 'announcement_access',
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
            $this->command->info("Permission Pengumuman ditambahkan ke role {$roleLabel}.");
        }

        $this->command->info('Selesai: permission announcement_* siap digunakan.');
    }
}
