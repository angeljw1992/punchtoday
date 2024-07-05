<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'user_management_access',
            'permission_create',
            'permission_edit',
            'permission_show',
            'permission_delete',
            'permission_access',
            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'role_access',
            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'user_access',
            'audit_log_show',
            'audit_log_access',
            'empleado_create',
            'empleado_edit',
            'empleado_show',
            'empleado_delete',
            'empleado_access',
            'profile_password_edit',
        ];

        Permission::truncate();
        foreach ($permissions as $permission) {
            Permission::create(['title' => $permission]);
        }
    }
}
