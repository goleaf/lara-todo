<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $user = new User();

        $this->assertEquals(['name', 'email', 'password', 'avatar'], $user->getFillable());
    }

    /** @test */
    public function it_can_create_todos()
    {
        $user = User::factory()->create();
        $todo = \App\Models\Todo::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->todos->contains($todo));
    }

    /** @test */
    public function it_can_create_categories()
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->categories->contains($category));
    }

    /** @test */
    public function it_can_create_tags()
    {
        $user = User::factory()->create();
        $tag = \App\Models\Tag::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->tags->contains($tag));
    }
}
