<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $categories = Category::where('user_id', $user->id)->get();
            $tags = Tag::where('user_id', $user->id)->get();

            Todo::factory()->count(5)->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
            ])->each(function ($todo) use ($tags) {
                $todo->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
            });
        }
    }
}
