<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSeeLivewire('profile.edit');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Livewire\Profile\UpdateProfileInformation::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->call('updateProfileInformation')
            ->assertDispatched('profile-updated');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Livewire\Profile\UpdateProfileInformation::class)
            ->set('name', 'Test User')
            ->set('email', $user->email)
            ->call('updateProfileInformation')
            ->assertDispatched('profile-updated');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Livewire\Profile\DeleteUser::class)
            ->set('password', 'password')
            ->call('deleteUser')
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        \Livewire\Livewire::actingAs($user)
            ->test(\App\Livewire\Profile\DeleteUser::class)
            ->set('password', 'wrong-password')
            ->call('deleteUser')
            ->assertHasErrors('password');

        $this->assertNotNull($user->fresh());
    }
}
