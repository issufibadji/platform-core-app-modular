<?php

namespace Modules\Notifications\Http\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ListNotifications extends Component
{
    use WithPagination;

    public bool $unreadOnly = false;

    public function updatedUnreadOnly(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function notifications()
    {
        $query = auth()->user()->notifications();

        if ($this->unreadOnly) {
            $query = auth()->user()->unreadNotifications();
        }

        return $query->paginate(20);
    }

    #[Computed]
    public function unreadCount(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function markRead(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        $this->resetPage();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->resetPage();
    }

    public function render()
    {
        return view('notifications::livewire.list-notifications');
    }
}
