<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Models\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::create(['name' => 'Bob', 'email' => 'bob@thats70show.com']);
        User::create(['name' => 'Eric', 'email' => 'eric@thats70show.com']);
    }
}
