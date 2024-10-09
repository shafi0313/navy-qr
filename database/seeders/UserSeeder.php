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
                'team' => null, // super admin
                'name' => 'Super Admin',
                'email' => 'super_admin@app.com',
                'mobile' => '01725848515',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            // Team A
            [
                'role_id' => 2,
                'team' => 'A', // admin
                'name' => 'Team A Admin',
                'email' => 'admin_a@app.com',
                'mobile' => '01725848515',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 3,
                'team' => 'A', // Team A Final Medical
                'name' => 'Team A Final Medical',
                'email' => 'f_medical_a@app.com',
                'mobile' => null,
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 4,
                'team' => 'A', // Team A Written Exam
                'name' => 'Team A Written Exam',
                'email' => 'written_a@app.com',
                'mobile' => null,
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 6,
                'team' => 'A', // Team A Preli Medical-1
                'name' => 'Team A Preli Medical-1',
                'email' => 'p_medical_a1@app.com',
                'mobile' => null,
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 6,
                'team' => 'A', // Team A Preli Medical-2
                'name' => 'Team A Preli Medical-2',
                'email' => 'p_medical_a2@app.com',
                'mobile' => null,
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 7,
                'team' => 'A', // Team A QR Code Scan-1
                'name' => 'Team A QR Code Scan-1',
                'email' => 'qr_a1@app.com',
                'mobile' => null,
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            [
                'role_id' => 7,
                'team' => 'A', // Team A QR Code Scan-2
                'name' => 'Team A QR Code Scan-2',
                'email' => 'qr_a2@app.com',
                'mobile' => null,
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
            // [
            //     'role_id' => 2,
            //     'name' => 'Normal User',
            //     'email' => 'normal@app.com',
            //     'user_name' => null,
            //     'gender' => Gender::MALE,
            //     'removable' => 0,
            //     'password' => bcrypt('##Zxc1234'),
            // ],
            // [
            //     'role_id' => 3,
            //     'name' => 'Primary Medical User',
            //     'email' => 'pmedical@app.com',
            //     'user_name' => null,
            //     'gender' => Gender::MALE,
            //     'removable' => 0,
            //     'password' => bcrypt('##Zxc1234'),
            // ],
            // [
            //     'role_id' => 4,
            //     'name' => 'Written User',
            //     'email' => 'written@app.com',
            //     'user_name' => null,
            //     'gender' => Gender::MALE,
            //     'removable' => 0,
            //     'password' => bcrypt('##Zxc1234'),
            // ],
            // [
            //     'role_id' => 5,
            //     'name' => 'Final Medical User',
            //     'email' => 'fmedical@app.com',
            //     'user_name' => null,
            //     'gender' => Gender::MALE,
            //     'removable' => 0,
            //     'password' => bcrypt('##Zxc1234'),
            // ],
            // [
            //     'role_id' => 6,
            //     'name' => 'Viva User',
            //     'email' => 'viva@app.com',
            //     'user_name' => null,
            //     'gender' => Gender::MALE,
            //     'removable' => 0,
            //     'password' => bcrypt('##Zxc1234'),
            // ],
        ];

        User::insert($admin);
    }
}
