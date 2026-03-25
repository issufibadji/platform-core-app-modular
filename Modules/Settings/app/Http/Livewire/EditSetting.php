<?php

namespace Modules\Settings\Http\Livewire;

use Livewire\Component;
use Modules\Settings\Models\Setting;
use Modules\Settings\Services\SettingService;

class EditSetting extends Component
{
    public Setting $setting;

    public string $value = '';
    public string $type  = 'string';
    public string $group = '';
    public bool   $is_public = false;

    public function mount(Setting $setting): void
    {
        $this->setting   = $setting;
        $this->value     = $setting->value ?? '';
        $this->type      = $setting->type  ?? 'string';
        $this->group     = $setting->group ?? '';
        $this->is_public = (bool) $setting->is_public;
    }

    public function save(): void
    {
        $this->validate([
            'value'     => 'nullable|string|max:65535',
            'type'      => 'required|string|in:string,boolean,integer,float,json',
            'group'     => 'nullable|string|max:100',
            'is_public' => 'boolean',
        ]);

        app(SettingService::class)->set(
            key:            $this->setting->key,
            value:          $this->value,
            type:           $this->type,
            organizationId: null,
            module:         $this->setting->module,
            group:          $this->group ?: null,
            isPublic:       $this->is_public,
        );

        $this->redirect(route('core.settings.index'), navigate: true);
    }

    public function render()
    {
        return view('settings::livewire.edit-setting');
    }
}
