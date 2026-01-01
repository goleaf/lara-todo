<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/todos', App\Livewire\Todos\Index::class)->name('todos.index');
    Route::get('/todos/create', App\Livewire\Todos\Create::class)->name('todos.create');
    Route::get('/todos/{todo}', App\Livewire\Todos\Show::class)->name('todos.show');
    Route::get('/todos/{todo}/edit', App\Livewire\Todos\Edit::class)->name('todos.edit');
    Route::get('/categories', App\Livewire\Categories\Index::class)->name('categories.index');
    Route::get('/categories/create', App\Livewire\Categories\Create::class)->name('categories.create');
    Route::get('/categories/{category}', App\Livewire\Categories\Show::class)->name('categories.show');
    Route::get('/categories/{category}/edit', App\Livewire\Categories\Edit::class)->name('categories.edit');
    Route::get('/tags', App\Livewire\Tags\Index::class)->name('tags.index');
    Route::get('/tags/create', App\Livewire\Tags\Create::class)->name('tags.create');
    Route::get('/tags/{tag}', App\Livewire\Tags\Show::class)->name('tags.show');
    Route::get('/tags/{tag}/edit', App\Livewire\Tags\Edit::class)->name('tags.edit');

    Route::get('/profile', App\Livewire\Profile\Edit::class)->name('profile.edit');
    // Keeping destroy for now if needed by other logic, but Livewire handles it internally.
    // Actually, Livewire sub-components handle update/destroy internally.
    // So we don't need dedicated routes for update/destroy unless used by other things.
    // Since we replaced the page with Edit component which contains sub-components, we are good.
});

require __DIR__ . '/auth.php';
