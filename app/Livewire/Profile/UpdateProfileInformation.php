<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformation extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->avatar) {
            $user->avatar = $this->avatar->store('avatars', 'public');
        }

        $user->save();

        session()->flash('status', 'profile-updated');
        $this->dispatch('profile-updated', name: $user->name);
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information');
    }
}
