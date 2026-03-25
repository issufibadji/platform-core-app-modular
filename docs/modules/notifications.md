# Notifications Module

**Path:** `Modules/Notifications/`
**Status:** Live
**Priority:** 17

---

## Purpose

Provides an internal notification centre for the current user using Laravel's built-in database notification system.

---

## Database

Uses Laravel's standard `notifications` table. Run the migration if not already done:

```bash
php artisan notifications:table
php artisan migrate
```

The `notifications` table has the following structure:

| Column | Type |
| --- | --- |
| id | uuid PK |
| type | string |
| notifiable_type | string |
| notifiable_id | bigint |
| data | text (json) |
| read_at | timestamp nullable |
| created_at / updated_at | timestamps |

---

## Model

No custom model. Uses Laravel's `DatabaseNotification` model via `auth()->user()->notifications()`.

---

## Sending Notifications

Create a standard Laravel notification:

```php
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserCreatedNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New user registered',
            'body'  => "User {$this->user->name} was created.",
        ];
    }
}

// Dispatch:
$admin->notify(new UserCreatedNotification($user));
```

The `title` and `body` keys in `data` are rendered by the `ListNotifications` component.

---

## Routes

| Name | Method | URL | Description |
| --- | --- | --- | --- |
| `core.notifications.index` | GET | `/core/notifications` | Notification centre for current user |

---

## Livewire Components

| Component | Tag | Description |
| --- | --- | --- |
| `ListNotifications` | `notifications.list-notifications` | Paginated list; unread badge; mark read; mark all read |

---

## Permissions

| Permission | Description |
| --- | --- |
| `core.notifications.view` | Access the notification centre |

---

## Limitations / Next Steps

- Email channel not wired — add `mail` to `via()` in notifications as needed
- No real-time push (Pusher/Echo) — page reload required
- No admin view of all users' notifications
