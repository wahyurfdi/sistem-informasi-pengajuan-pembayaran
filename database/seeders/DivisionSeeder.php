<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisions = [
            [
                'id' => 1,
                'code' => 'DV00001',
                'name' => 'Finance',
            ],
            [
                'id' => 2,
                'code' => 'DV00002',
                'name' => 'Technology & Information',
            ],
            [
                'id' => 3,
                'code' => 'DV00003',
                'name' => 'HRD',
            ],
            [
                'id' => 4,
                'code' => 'DV00004',
                'name' => 'Marketing',
            ],
            [
                'id' => 5,
                'code' => 'DV00005',
                'name' => 'Production',
            ],
        ];

        foreach($divisions as $division) {
            $division['created_at'] = date('Y-m-d H:i:s');
            $division['updated_at'] = date('Y-m-d H:i:s');

            Division::create($division);
        }
    }
}
