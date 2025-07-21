<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Blog::create([
            'user_id' => 1,
            'title' => 'First Blog',
            'description' => 'This is the first blog.',
            'image' => null,
        ]);
        \App\Models\Blog::create([
            'user_id' => 1,
            'title' => 'Second Blog',
            'description' => 'This is the second blog.',
            'image' => null,
        ]);
    }
}
