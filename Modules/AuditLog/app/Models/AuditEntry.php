<?php

namespace Modules\AuditLog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditEntry extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'tags'       => 'array',
    ];

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    // ---------------------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------------------

    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('event', 'like', "%{$term}%")
              ->orWhere('auditable_type', 'like', "%{$term}%")
              ->orWhere('ip_address', 'like', "%{$term}%");
        });
    }
}
