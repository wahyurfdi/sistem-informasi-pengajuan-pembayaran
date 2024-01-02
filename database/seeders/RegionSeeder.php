<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            [
                'id' => 1,
                'code' => 'BDG00000',
                'name' => 'Bandung',
            ],
            [
                'id' => 2,
                'code' => 'BDG00001',
                'name' => 'Bandung Barat',
            ],
            [
                'id' => 3,
                'code' => 'TSK00000',
                'name' => 'Tasikmalaya',
            ],
            [
                'id' => 4,
                'code' => 'PDL00000',
                'name' => 'Padalarang',
            ]
        ];

        foreach($regions as $region) {
            $region['created_at'] = date('Y-m-d H:i:s');
            $region['updated_at'] = date('Y-m-d H:i:s');

            Region::create($region);
        }
    }
}
