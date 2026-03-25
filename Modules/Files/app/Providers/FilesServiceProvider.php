<?php

namespace Modules\Files\Providers;

use Livewire\Livewire;
use Modules\Files\Http\Livewire\ListFiles;
use Modules\Files\Http\Livewire\UploadFile;
use Nwidart\Modules\Support\ModuleServiceProvider;

class FilesServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Files';

    protected string $nameLower = 'files';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function boot(): void
    {
        parent::boot();

        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('files.list-files',   ListFiles::class);
        Livewire::component('files.upload-file',  UploadFile::class);
    }
}
