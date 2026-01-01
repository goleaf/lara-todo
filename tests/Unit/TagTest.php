<?php

namespace Tests\Unit;

use App\Models\Tag;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $tag = new Tag();

        $this->assertEquals(['name', 'color', 'user_id'], $tag->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = \App\Models\User::factory()->create();
        $tag = Tag::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\App\Models\User::class, $tag->user);
    }

    /** @test */
    public function it_can_be_attached_to_todos()
    {
        $tag = Tag::factory()->create();
        $todo = Todo::factory()->create();

        $todo->tags()->attach($tag);

        $this->assertTrue($tag->todos->contains($todo));
    }
}
