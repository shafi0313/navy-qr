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
        $admin = [
            [
                'role_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@app.com',
                'mobile' => '01725848515',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 2,
                'name' => 'Normal User',
                'email' => 'normal@app.com',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 3,
                'name' => 'Primary Medical User',
                'email' => 'pmedical@app.com',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 4,
                'name' => 'Written User',
                'email' => 'written@app.com',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 5,
                'name' => 'Final Medical User',
                'email' => 'fmedical@app.com',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 6,
                'name' => 'Viva User',
                'email' => 'viva@app.com',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
        ];

        User::insert($admin);
    }
}
