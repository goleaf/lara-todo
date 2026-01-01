<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $todo->user);
        $this->assertEquals($user->id, $todo->user->id);
    }

    /** @test */
    public function it_can_have_tags()
    {
        $todo = Todo::factory()->create();
        $tag = \App\Models\Tag::factory()->create();

        $todo->tags()->attach($tag);

        $this->assertTrue($todo->tags->contains($tag));
    }

    /** @test */
    public function it_can_have_a_category()
    {
        $category = \App\Models\Category::factory()->create();
        $todo = Todo::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(\App\Models\Category::class, $todo->category);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $todo = new Todo();

        $this->assertEquals([
            'user_id',
            'category_id',
            'title',
            'description',
            'status',
            'due_date',
            'progress',
        ], $todo->getFillable());
    }
}
