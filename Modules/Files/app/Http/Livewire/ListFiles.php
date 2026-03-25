<?php

namespace Modules\Files\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Files\Models\File;
use Modules\Files\Services\FileService;

class ListFiles extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public ?int $confirmingDelete = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function files()
    {
        return File::query()
            ->with('uploader')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->latest()
            ->paginate(20);
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = null;
    }

    public function delete(): void
    {
        $file = File::find($this->confirmingDelete);

        if ($file) {
            app(FileService::class)->delete($file);
        }

        $this->confirmingDelete = null;
        $this->resetPage();
    }

    public function render()
    {
        return view('files::livewire.list-files');
    }
}
