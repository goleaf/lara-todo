<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_tags()
    {
        $this->actingAs($this->user);
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        $this->get('/tags')
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_create_tag()
    {
        $this->actingAs($this->user);

        \Livewire\Livewire::test(\App\Livewire\Tags\Create::class)
            ->set('name', 'New Tag')
            ->set('color', 'blue')
            ->call('save')
            ->assertRedirect(route('tags.index'));

        $this->assertDatabaseHas('tags', ['name' => 'New Tag', 'user_id' => $this->user->id]);
    }

    /** @test */
    public function user_can_update_tag()
    {
        $this->actingAs($this->user);
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        \Livewire\Livewire::test(\App\Livewire\Tags\Edit::class, ['tag' => $tag])
            ->set('name', 'Updated Tag')
            ->set('color', 'red')
            ->call('save')
            ->assertRedirect(route('tags.index'));

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Updated Tag', 'color' => 'red']);
    }

    /** @test */
    public function user_can_delete_tag()
    {
        $this->actingAs($this->user);
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        // Using Index component for delete with new modal confirmation flow
        \Livewire\Livewire::test(\App\Livewire\Tags\Index::class)
            ->call('confirmDelete', $tag->id, $tag->name)
            ->call('delete');

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function user_cannot_view_other_users_tag()
    {
        $otherUser = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->get("/tags/{$tag->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function user_cannot_update_other_users_tag()
    {
        $otherUser = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        $this->get(route('tags.edit', $tag))
            ->assertStatus(403);
    }

    /** @test */
    public function user_cannot_delete_other_users_tag()
    {
        $otherUser = User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        // Attempting to delete via Index component with the new flow
        \Livewire\Livewire::test(\App\Livewire\Tags\Index::class)
            ->call('confirmDelete', $tag->id, $tag->name)
            ->call('delete')
            ->assertForbidden();

        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function user_cannot_create_duplicate_tag_name()
    {
        $this->actingAs($this->user);
        Tag::factory()->create(['user_id' => $this->user->id, 'name' => 'Duplicate']);

        \Livewire\Livewire::test(\App\Livewire\Tags\Create::class)
            ->set('name', 'Duplicate')
            ->set('color', 'red')
            ->call('save')
            ->assertHasErrors('name');
    }

    /** @test */
    public function different_users_can_have_same_tag_name()
    {
        $otherUser = User::factory()->create();
        Tag::factory()->create(['user_id' => $otherUser->id, 'name' => 'Same Name']);

        $this->actingAs($this->user);

        \Livewire\Livewire::test(\App\Livewire\Tags\Create::class)
            ->set('name', 'Same Name')
            ->set('color', 'blue')
            ->call('save')
            ->assertRedirect(route('tags.index'));

        $this->assertDatabaseHas('tags', ['user_id' => $this->user->id, 'name' => 'Same Name']);
    }
}
