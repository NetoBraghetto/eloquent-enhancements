<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Models\PhoneType;

class PhoneTypeSeeder extends Seeder
{
    public function run()
    {
        PhoneType::create(['name' => 'Phone']);
        PhoneType::create(['name' => 'Celphone']);
    }
}
