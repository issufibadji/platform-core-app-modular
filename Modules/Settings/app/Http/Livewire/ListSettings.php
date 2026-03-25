<?php

namespace Modules\Settings\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Settings\Models\Setting;

class ListSettings extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $group = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGroup(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function settings()
    {
        return Setting::query()
            ->whereNull('organization_id')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('key', 'like', "%{$this->search}%")
                  ->orWhere('value', 'like', "%{$this->search}%")
                  ->orWhere('group', 'like', "%{$this->search}%");
            }))
            ->when($this->group, fn ($q) => $q->where('group', $this->group))
            ->orderBy('group')
            ->orderBy('key')
            ->paginate(20);
    }

    #[Computed]
    public function groups(): array
    {
        return Setting::query()
            ->whereNull('organization_id')
            ->whereNotNull('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();
    }

    public function render()
    {
        return view('settings::livewire.list-settings');
    }
}
