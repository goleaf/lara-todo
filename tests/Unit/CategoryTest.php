<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $category = new Category();

        $this->assertEquals(['name', 'user_id', 'color'], $category->getFillable());
    }



    /** @test */
    public function it_can_have_todos()
    {
        $category = Category::factory()->create();
        $todo = Todo::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->todos->contains($todo));
    }
}
