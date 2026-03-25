<?php

namespace Modules\Roles\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class ListRoles extends Component
{
    #[Computed]
    public function roles()
    {
        return Role::withCount('permissions', 'users')->orderBy('name')->get();
    }

    public function render()
    {
        return view('roles::livewire.list-roles');
    }
}
