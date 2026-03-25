<?php

namespace Modules\Roles\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    public string $name = '';
    public array $selectedPermissions = [];

    #[Computed]
    public function permissions()
    {
        return Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0] . '.' . (explode('.', $p->name)[1] ?? '');
        });
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
        $role->syncPermissions($this->selectedPermissions);

        $this->redirect(route('core.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('roles::livewire.create-role');
    }
}
