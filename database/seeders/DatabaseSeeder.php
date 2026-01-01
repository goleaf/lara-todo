<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\TodoSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'user1@test.com'],
            [
                'name' => 'Test User 1',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Create 15 categories for this user
        $categories = \App\Models\Category::factory()->count(15)->create(['user_id' => $user->id]);

        // Create 10 tags for this user
        $tags = \App\Models\Tag::factory()->count(10)->create(['user_id' => $user->id]);

        // Create 100 todos for this user
        \App\Models\Todo::factory()->count(100)->create([
            'user_id' => $user->id,
            'category_id' => fn() => $categories->random()->id,
        ])->each(function ($todo) use ($tags) {
            $todo->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        });
    }
}
