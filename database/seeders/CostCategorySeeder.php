<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CostCategory;

class CostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $costCategories = [
            ['name' => 'Kesehatan'],
            ['name' => 'Transportasi'],
            ['name' => 'Konsumsi'],
            ['name' => 'Operasional']
        ];

        foreach($costCategories as $costCategory) {
            $costCategory['created_at'] = date('Y-m-d H:i:s');
            $costCategory['updated_at'] = date('Y-m-d H:i:s');

            CostCategory::create($costCategory);
        }
    }
}
