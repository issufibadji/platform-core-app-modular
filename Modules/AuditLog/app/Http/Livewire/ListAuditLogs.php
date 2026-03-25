<?php

namespace Modules\AuditLog\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\AuditLog\Models\AuditEntry;

class ListAuditLogs extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $event = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedEvent(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function logs()
    {
        return AuditEntry::query()
            ->with('user')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->event,  fn ($q) => $q->forEvent($this->event))
            ->latest()
            ->paginate(25);
    }

    #[Computed]
    public function events(): array
    {
        return AuditEntry::query()
            ->distinct()
            ->orderBy('event')
            ->pluck('event')
            ->toArray();
    }

    public function render()
    {
        return view('auditlog::livewire.list-audit-logs');
    }
}
