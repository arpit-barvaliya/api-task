<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Like::create([
            'user_id' => 1,
            'likeable_id' => 1,
            'likeable_type' => \App\Models\Blog::class,
        ]);
        \App\Models\Like::create([
            'user_id' => 1,
            'likeable_id' => 2,
            'likeable_type' => \App\Models\Blog::class,
        ]);
    }
}
