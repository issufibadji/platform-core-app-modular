<?php

namespace Modules\Files\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Files\Services\FileService;

class UploadFile extends Component
{
    use WithFileUploads;

    public $file = null;
    public string $disk       = 'local';
    public string $directory  = 'uploads';
    public string $visibility = 'private';

    public function save(): void
    {
        $this->validate([
            'file'       => 'required|file|max:10240', // 10 MB
            'disk'       => 'required|string|in:local,public',
            'directory'  => 'required|string|max:255',
            'visibility' => 'required|string|in:private,public',
        ]);

        app(FileService::class)->store(
            upload:       $this->file,
            disk:         $this->disk,
            directory:    $this->directory,
            visibility:   $this->visibility,
        );

        $this->redirect(route('core.files.index'), navigate: true);
    }

    public function render()
    {
        return view('files::livewire.upload-file');
    }
}
