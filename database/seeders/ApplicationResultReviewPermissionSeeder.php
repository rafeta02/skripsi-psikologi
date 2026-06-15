<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ApplicationResultReviewPermissionSeeder extends Seeder
{
    /**
     * Tambahkan permission ApplicationResultReview dan assign ke role Admin & User.
     * Aman dijalankan ulang pada database yang sudah ada:
     *   php artisan db:seed --class=ApplicationResultReviewPermissionSeeder
     */
    public function run()
    {
        $permissions = [
            145 => 'application_result_review_create',
            146 => 'application_result_review_edit',
            147 => 'application_result_review_show',
            148 => 'application_result_review_delete',
            149 => 'application_result_review_access',
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
            $this->command->info("Permission ApplicationResultReview ditambahkan ke role {$roleLabel}.");
        }

        $this->command->info('Selesai: permission application_result_review_* siap digunakan.');
    }
}
