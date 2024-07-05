<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'title' => 'Admin',
        ];

        Role::create($roles);

        $roles = [
            'title' => 'User',
        ];

        Role::create($roles);
    }
}
