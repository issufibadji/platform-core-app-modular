<?php

namespace Modules\Permissions\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class ListPermissions extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Computed]
    public function permissionGroups()
    {
        return Permission::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($p) => explode('.', $p->name)[0] ?? 'other');
    }

    public function render()
    {
        return view('permissions::livewire.list-permissions');
    }
}
