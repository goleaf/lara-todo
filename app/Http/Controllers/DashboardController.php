<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = auth()->user();

        $stats = [
            'total_todos' => $user->todos()->count(),
            'pending_todos' => $user->todos()->where('status', 'pending')->count(),
            'completed_todos' => $user->todos()->where('status', 'completed')->count(),
            'total_categories' => $user->categories()->count(),
            'total_tags' => $user->tags()->count(),
        ];

        $recent_todos = $user->todos()
            ->with(['category', 'tags'])
            ->latest()
            ->take(5)
            ->get();

        $categories = $user->categories;
        $tags = $user->tags;

        return view('dashboard', compact('stats', 'recent_todos', 'categories', 'tags'));
    }
}
