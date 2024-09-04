<?php

namespace Database\Seeders;

use App\Models\User;
use App\Constants\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'user_name' => null,
            'gender' => Gender::MALE,
            'removable' => 0,
            'password' => bcrypt('##Zxc1234'),
        ]);
    }
}
