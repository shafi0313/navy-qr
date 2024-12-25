<?php

namespace Database\Seeders;

use App\Constants\Gender;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $admin = [
        //     [
        //         'role_id' => 1,
        //         'team' => null, // super admin
        //         'name' => 'Super Admin',
        //         'email' => 'super_admin@app.com',
        //         'mobile' => '01725848515',
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     // Team A
        //     [
        //         'role_id' => 2,
        //         'team' => 'A', // admin
        //         'name' => 'Team A Admin',
        //         'email' => 'admin_a@app.com',
        //         'mobile' => '01725848515',
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 3,
        //         'team' => 'A', // Team A Final Medical
        //         'name' => 'Team A Final Medical',
        //         'email' => 'f_medical_a@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 4,
        //         'team' => 'A', // Team A Written Exam
        //         'name' => 'Team A Written Exam',
        //         'email' => 'written_a@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 6,
        //         'team' => 'A', // Team A Preli Medical-1
        //         'name' => 'Team A Preli Medical-1',
        //         'email' => 'p_medical_a1@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 6,
        //         'team' => 'A', // Team A Preli Medical-2
        //         'name' => 'Team A Preli Medical-2',
        //         'email' => 'p_medical_a2@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 7,
        //         'team' => 'A', // Team A QR Code Scan-1
        //         'name' => 'Team A QR Code Scan-1',
        //         'email' => 'qr_a1@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 7,
        //         'team' => 'A', // Team A QR Code Scan-2
        //         'name' => 'Team A QR Code Scan-2',
        //         'email' => 'qr_a2@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],

        //     // Team B
        //     [
        //         'role_id' => 2,
        //         'team' => 'B', // admin
        //         'name' => 'Team B Admin',
        //         'email' => 'admin_b@app.com',
        //         'mobile' => '01725848515',
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 3,
        //         'team' => 'B', // Team A Final Medical
        //         'name' => 'Team A Final Medical',
        //         'email' => 'f_medical_b@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 4,
        //         'team' => 'B', // Team A Written Exam
        //         'name' => 'Team A Written Exam',
        //         'email' => 'written_b@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 6,
        //         'team' => 'B', // Team A Preli Medical-1
        //         'name' => 'Team A Preli Medical-1',
        //         'email' => 'p_medical_b1@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 6,
        //         'team' => 'B', // Team A Preli Medical-2
        //         'name' => 'Team A Preli Medical-2',
        //         'email' => 'p_medical_b2@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 7,
        //         'team' => 'B', // Team A QR Code Scan-1
        //         'name' => 'Team A QR Code Scan-1',
        //         'email' => 'qr_b1@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 7,
        //         'team' => 'B', // Team A QR Code Scan-2
        //         'name' => 'Team A QR Code Scan-2',
        //         'email' => 'qr_b2@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],

        //     // Team C
        //     [
        //         'role_id' => 2,
        //         'team' => 'C', // admin
        //         'name' => 'Team C Admin',
        //         'email' => 'admin_c@app.com',
        //         'mobile' => '01725848515',
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 3,
        //         'team' => 'C', // Team A Final Medical
        //         'name' => 'Team C Final Medical',
        //         'email' => 'f_medical_c@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 4,
        //         'team' => 'C', // Team A Written Exam
        //         'name' => 'Team C Written Exam',
        //         'email' => 'written_c@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 6,
        //         'team' => 'C', // Team A Preli Medical-1
        //         'name' => 'Team C Preli Medical-1',
        //         'email' => 'p_medical_c1@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 6,
        //         'team' => 'C', // Team A Preli Medical-2
        //         'name' => 'Team C Preli Medical-2',
        //         'email' => 'p_medical_c2@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 7,
        //         'team' => 'C', // Team A QR Code Scan-1
        //         'name' => 'Team C QR Code Scan-1',
        //         'email' => 'qr_c1@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        //     [
        //         'role_id' => 7,
        //         'team' => 'C', // Team A QR Code Scan-2
        //         'name' => 'Team C QR Code Scan-2',
        //         'email' => 'qr_c2@app.com',
        //         'mobile' => null,
        //         'user_name' => null,
        //         'gender' => Gender::MALE,
        //         'removable' => 0,
        //         'password' => bcrypt('##Zxc1234'),
        //     ],
        // ];

        $admin = [
            [
                'role_id' => 1,
                'exam_type' => 2,
                'team' => null, // super admin
                'name' => 'Super Admin for Officer Data',
                'email' => 'o_super_admin@app.com',
                'mobile' => '01725848515',
                'user_name' => null,
                'gender' => Gender::MALE,
                'removable' => 0,
                'password' => bcrypt('##Zxc1234'),
            ],
        ];

        User::insert($admin);
    }
}
