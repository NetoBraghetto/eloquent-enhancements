<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            RegionSeeder::class,
            PhoneTypeSeeder::class,
            UsersSeeder::class,
            PostsSeeder::class,
        ]);
    }
}
