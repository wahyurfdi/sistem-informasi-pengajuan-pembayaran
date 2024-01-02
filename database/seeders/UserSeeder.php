<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'code' => 'EMP00001',
                'name' => 'Super Admin',
                'email' => 'admin',
                'password' => bcrypt('admin123'),
                'division_id' => 2,
                'region_id' => 1,
                'role' => 'SUPER_ADMIN'
            ],
            [
                'code' => 'EMP00002',
                'name' => 'Wahyu Rifaldi',
                'email' => 'wahyu',
                'password' => bcrypt('wahyu123'),
                'division_id' => 2,
                'region_id' => 1,
                'role' => 'SPV_EMPLOYEE'
            ],
            [
                'code' => 'EMP00003',
                'name' => 'Fadli M Ghifari',
                'email' => 'fadli',
                'password' => bcrypt('fadli123'),
                'division_id' => 1,
                'region_id' => 1,
                'role' => 'FINANCE_STAFF'
            ],
            [
                'code' => 'EMP00004',
                'name' => 'Anisa Indriani',
                'email' => 'anisa',
                'password' => bcrypt('anisa123'),
                'division_id' => 5,
                'region_id' => 2,
                'role' => 'EMPLOYEE'
            ],
            [
                'code' => 'EMP00005',
                'name' => 'Shelvia Nur Widiastuti',
                'email' => 'shelvia',
                'password' => bcrypt('shelvia123'),
                'division_id' => 4,
                'region_id' => 3,
                'role' => 'EMPLOYEE'
            ],
        ];

        foreach($users as $user) {
            $user['created_at'] = date('Y-m-d H:i:s');
            $user['updated_at'] = date('Y-m-d H:i:s');

            User::create($user);
        }
    }
}
