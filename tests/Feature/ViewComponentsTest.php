<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewComponentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_layout_component_can_be_rendered()
    {
        $user = User::factory()->create();

        $view = $this->actingAs($user)->view('layouts.app', [
            'header' => 'Test Header',
            'slot' => 'Test Content'
        ]);

        $view->assertSee('Test Header');
        $view->assertSee('Test Content');
    }

    public function test_guest_layout_component_can_be_rendered()
    {
        $view = $this->view('layouts.guest', [
            'slot' => 'Guest Content'
        ]);

        $view->assertSee('Guest Content');
    }
}
