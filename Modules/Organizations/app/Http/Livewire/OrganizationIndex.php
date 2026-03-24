<?php

namespace Modules\Organizations\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Organizations\Services\OrganizationService;

#[Layout('layouts.app')]
#[Title('Organizations')]
class OrganizationIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(OrganizationService $service)
    {
        return view('organizations::livewire.organization-index', [
            'organizations' => $service->paginate(perPage: 15, search: $this->search),
        ]);
    }
}
