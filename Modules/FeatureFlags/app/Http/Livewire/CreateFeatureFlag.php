<?php

namespace Modules\FeatureFlags\Http\Livewire;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Modules\FeatureFlags\Models\FeatureFlag;
use Modules\FeatureFlags\Services\FeatureFlagService;

class CreateFeatureFlag extends Component
{
    #[Rule('required|string|max:128|regex:/^[a-z0-9._-]+$/')]
    public string $key = '';

    #[Rule('nullable|string|max:255')]
    public string $description = '';

    #[Rule('nullable|string|max:64')]
    public string $module = '';

    #[Rule('boolean')]
    public bool $is_enabled = false;

    public function save(): void
    {
        $data = $this->validate();

        FeatureFlag::create([
            'key'             => $data['key'],
            'description'     => $data['description'] ?: null,
            'module'          => $data['module'] ?: null,
            'organization_id' => null,
            'is_enabled'      => $data['is_enabled'],
        ]);

        session()->flash('success', __('Feature flag created successfully.'));

        $this->redirect(route('core.featureflags.index'), navigate: true);
    }

    public function render()
    {
        return view('featureflags::livewire.create-feature-flag');
    }
}
