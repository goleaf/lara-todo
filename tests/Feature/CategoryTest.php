<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_categories()
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $this->get('/categories')
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_create_category()
    {
        $this->actingAs($this->user);

        \Livewire\Livewire::test(\App\Livewire\Categories\Create::class)
            ->set('name', 'New Category')
            ->call('save')
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'New Category', 'user_id' => $this->user->id]);
    }

    /** @test */
    public function user_can_update_category()
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        \Livewire\Livewire::test(\App\Livewire\Categories\Edit::class, ['category' => $category])
            ->set('name', 'Updated Category')
            ->call('save')
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Category']);
    }

    /** @test */
    public function user_can_delete_category()
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        // Using Index component for delete as per implementation in Index.php
        \Livewire\Livewire::test(\App\Livewire\Categories\Index::class)
            ->call('delete', $category->id);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function user_cannot_view_other_users_category()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->get("/categories/{$category->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function user_cannot_update_other_users_category()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        // Authorization check happens on mount or update
        // We can test that we can't access the edit page
        $this->get(route('categories.edit', $category))
            ->assertStatus(403);
    }

    /** @test */
    public function user_cannot_delete_other_users_category()
    {
        $otherUser = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        // Attempting to delete via Index component
        \Livewire\Livewire::test(\App\Livewire\Categories\Index::class)
            ->call('delete', $category->id)
            ->assertForbidden(); // Should fail authorization

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function user_cannot_create_duplicate_category_name()
    {
        $this->actingAs($this->user);
        Category::factory()->create(['user_id' => $this->user->id, 'name' => 'Duplicate']);

        \Livewire\Livewire::test(\App\Livewire\Categories\Create::class)
            ->set('name', 'Duplicate')
            ->call('save')
            ->assertHasErrors('name');
    }

    /** @test */
    public function different_users_can_have_same_category_name()
    {
        $otherUser = User::factory()->create();
        Category::factory()->create(['user_id' => $otherUser->id, 'name' => 'Same Name']);

        $this->actingAs($this->user);

        \Livewire\Livewire::test(\App\Livewire\Categories\Create::class)
            ->set('name', 'Same Name')
            ->call('save')
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['user_id' => $this->user->id, 'name' => 'Same Name']);
    }
}
