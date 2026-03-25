<?php

namespace Modules\Users\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Modules\Organizations\Models\Organization;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    public ?User $user = null;

    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $organization_id = null;
    public string $role = '';

    public function mount(?User $user = null): void
    {
        if ($user && $user->exists) {
            $this->user  = $user;
            $this->name  = $user->name;
            $this->email = $user->email;
            $this->role  = $user->getRoleNames()->first() ?? '';
        }
    }

    public function save(): void
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($this->user ? ",{$this->user->id}" : ''),
            'role'  => 'nullable|string|exists:roles,name',
        ];

        if (! $this->user) {
            $rules['password']              = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        $this->validate($rules);

        if ($this->user) {
            $this->user->update(['name' => $this->name, 'email' => $this->email]);
            if ($this->role) {
                $this->user->syncRoles([$this->role]);
            }
        } else {
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => bcrypt($this->password),
            ]);

            if ($this->role) {
                $user->assignRole($this->role);
            }

            if ($this->organization_id) {
                $user->organizations()->attach($this->organization_id, [
                    'status'    => 'active',
                    'joined_at' => now(),
                ]);
            }
        }

        $this->redirect(route('core.users.index'), navigate: true);
    }

    public function render()
    {
        return view('users::livewire.create-user', [
            'organizations' => Organization::active()->orderBy('name')->get(),
            'roles'         => Role::orderBy('name')->pluck('name'),
        ]);
    }
}
