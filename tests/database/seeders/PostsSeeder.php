<?php

namespace Database\Seeders;
use Database\Models\Post;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run()
    {
        Post::Create(['title' => 'Do you like PHP?', 'content' => 'This is my first post']);
        Post::Create(['title' => 'Do you like JS?', 'content' => 'Second post :)']);
    }
}
