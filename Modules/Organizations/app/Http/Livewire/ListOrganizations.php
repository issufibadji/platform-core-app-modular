<?php

namespace Modules\Organizations\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Organizations\Enums\OrganizationStatus;
use Modules\Organizations\Models\Organization;
use Modules\Organizations\Services\OrganizationService;

class ListOrganizations extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    public bool $confirmingDelete = false;

    public ?int $deletingId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function organizations()
    {
        return Organization::query()
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function statuses(): array
    {
        return OrganizationStatus::cases();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
        $this->confirmingDelete = false;
    }

    public function delete(): void
    {
        $organization = Organization::findOrFail($this->deletingId);
        app(OrganizationService::class)->delete($organization);
        $this->cancelDelete();
    }

    public function render()
    {
        return view('organizations::livewire.list-organizations');
    }
}
