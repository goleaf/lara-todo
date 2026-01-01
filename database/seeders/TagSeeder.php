<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['gray', 'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];

        $users = User::all();

        $tagNames = ['Urgent', 'Meeting', 'Email', 'Call', 'Review', 'Bug', 'Feature'];

        foreach ($users as $user) {
            foreach ($tagNames as $name) {
                Tag::create([
                    'name' => $name,
                    'color' => $colors[array_rand($colors)],
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
