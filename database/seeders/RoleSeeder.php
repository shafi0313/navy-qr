<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'is_active' => 1,
            ],
            [
                'name' => 'Normal User',
                'is_active' => 1,
            ],
            [
                'name' => 'Primary Medical',
                'is_active' => 1,
            ],
            [
                'name' => 'Written',
                'is_active' => 1,
            ],
            [
                'name' => 'Final Medical',
                'is_active' => 1,
            ],
            [
                'name' => 'Viva/Final Selection',
                'is_active' => 1,
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
