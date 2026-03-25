<?php

namespace Modules\FeatureFlags\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\FeatureFlags\Models\FeatureFlag;
use Modules\FeatureFlags\Services\FeatureFlagService;

class ListFeatureFlags extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    #[Computed]
    public function flags()
    {
        return FeatureFlag::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('key', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('module', 'like', "%{$this->search}%");
            }))
            ->when($this->status === 'enabled',  fn ($q) => $q->where('is_enabled', true))
            ->when($this->status === 'disabled', fn ($q) => $q->where('is_enabled', false))
            ->orderBy('key')
            ->paginate(25);
    }

    public function toggle(int $id): void
    {
        $flag = FeatureFlag::findOrFail($id);
        $flag->update(['is_enabled' => ! $flag->is_enabled]);
    }

    public function delete(int $id): void
    {
        FeatureFlag::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('featureflags::livewire.list-feature-flags');
    }
}
