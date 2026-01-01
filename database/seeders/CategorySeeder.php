<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $categoryNames = ['Work', 'Personal', 'Shopping', 'Health', 'Learning'];

        foreach ($users as $user) {
            foreach ($categoryNames as $name) {
                Category::create([
                    'name' => $name,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
