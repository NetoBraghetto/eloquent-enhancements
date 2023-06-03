<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Models\Region;

class RegionSeeder extends Seeder
{
    public function run()
    {
        $region = Region::create(['name' => 'Ribeirão Preto']);
        $region->cities()->sync([1, 2, 3, 4, 5]);
    }
}
