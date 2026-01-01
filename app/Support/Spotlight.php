<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class Spotlight
{
    public function search(Request $request)
    {
        // Security: Only authenticated users can search
        if (!auth()->user()) {
            return [];
        }

        return collect()
            ->merge($this->actions($request->search))
            ->merge($this->todos($request->search))
            ->merge($this->categories($request->search))
            ->merge($this->tags($request->search));
    }

    /**
     * Search todos
     */
    private function todos(string $search = '')
    {
        return Todo::query()
            ->where('user_id', auth()->id())
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->with('category')
            ->take(5)
            ->get()
            ->map(function (Todo $todo) {
                return [
                    'name' => $todo->title,
                    'description' => $todo->category?->name ?? 'Todo',
                    'link' => route('todos.show', $todo),
                    'icon' => Blade::render("<x-icon name='o-check-circle' class='w-11 h-11 p-2 " .
                        ($todo->status === 'completed' ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning') .
                        " rounded-full' />"),
                ];
            });
    }

    /**
     * Search categories
     */
    private function categories(string $search = '')
    {
        return Category::query()
            ->where('user_id', auth()->id())
            ->where('name', 'like', "%{$search}%")
            ->take(3)
            ->get()
            ->map(function (Category $category) {
                return [
                    'name' => $category->name,
                    'description' => 'Category',
                    'link' => route('categories.show', $category),
                    'icon' => Blade::render("<x-icon name='o-folder' class='w-11 h-11 p-2 bg-primary/20 text-primary rounded-full' />"),
                ];
            });
    }

    /**
     * Search tags
     */
    private function tags(string $search = '')
    {
        return Tag::query()
            ->where('user_id', auth()->id())
            ->where('name', 'like', "%{$search}%")
            ->take(3)
            ->get()
            ->map(function (Tag $tag) {
                return [
                    'name' => $tag->name,
                    'description' => 'Tag',
                    'link' => route('tags.show', $tag),
                    'icon' => Blade::render("<x-icon name='o-tag' class='w-11 h-11 p-2 bg-secondary/20 text-secondary rounded-full' />"),
                ];
            });
    }

    /**
     * App-wide actions
     */
    private function actions(string $search = '')
    {
        $actions = collect([
            [
                'name' => 'Create Todo',
                'description' => 'Create a new task',
                'link' => route('todos.create'),
                'icon' => Blade::render("<x-icon name='o-plus-circle' class='w-11 h-11 p-2 bg-success/20 text-success rounded-full' />"),
            ],
            [
                'name' => 'Create Category',
                'description' => 'Create a new category',
                'link' => route('categories.create'),
                'icon' => Blade::render("<x-icon name='o-folder-plus' class='w-11 h-11 p-2 bg-primary/20 text-primary rounded-full' />"),
            ],
            [
                'name' => 'Create Tag',
                'description' => 'Create a new tag',
                'link' => route('tags.create'),
                'icon' => Blade::render("<x-icon name='o-plus' class='w-11 h-11 p-2 bg-secondary/20 text-secondary rounded-full' />"),
            ],
            [
                'name' => 'Dashboard',
                'description' => 'View your dashboard',
                'link' => route('dashboard'),
                'icon' => Blade::render("<x-icon name='o-home' class='w-11 h-11 p-2 bg-info/20 text-info rounded-full' />"),
            ],
            [
                'name' => 'All Todos',
                'description' => 'View all your tasks',
                'link' => route('todos.index'),
                'icon' => Blade::render("<x-icon name='o-list-bullet' class='w-11 h-11 p-2 bg-warning/20 text-warning rounded-full' />"),
            ],
            [
                'name' => 'Profile Settings',
                'description' => 'Update your profile',
                'link' => route('profile.edit'),
                'icon' => Blade::render("<x-icon name='o-user-circle' class='w-11 h-11 p-2 bg-neutral/20 rounded-full' />"),
            ],
        ]);

        return $actions->filter(
            fn(array $item) =>
            str($item['name'] . ' ' . $item['description'])->contains($search, true)
        );
    }
}
