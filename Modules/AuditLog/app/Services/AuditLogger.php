<?php

namespace Modules\AuditLog\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\AuditLog\Models\AuditEntry;

class AuditLogger
{
    /**
     * Record an audit entry.
     *
     * @param  string       $event       e.g. 'created', 'updated', 'deleted'
     * @param  Model|null   $auditable   The model being audited
     * @param  array        $oldValues   Previous attribute values (for updates)
     * @param  array        $newValues   New attribute values
     * @param  array        $tags        Optional free-form tags
     */
    public function log(
        string  $event,
        ?Model  $auditable = null,
        array   $oldValues = [],
        array   $newValues = [],
        array   $tags      = [],
    ): AuditEntry {
        return AuditEntry::create([
            'user_id'        => auth()->id(),
            'event'          => $event,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id'   => $auditable?->getKey(),
            'old_values'     => $oldValues ?: null,
            'new_values'     => $newValues ?: null,
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'tags'           => $tags ?: null,
        ]);
    }

    /**
     * Convenience: log creation.
     */
    public function created(Model $model, array $attributes = []): AuditEntry
    {
        return $this->log('created', $model, [], $attributes ?: $model->getAttributes());
    }

    /**
     * Convenience: log update with dirty tracking.
     */
    public function updated(Model $model, array $dirty = []): AuditEntry
    {
        $old = [];
        $new = $dirty ?: $model->getDirty();

        foreach (array_keys($new) as $key) {
            $old[$key] = $model->getOriginal($key);
        }

        return $this->log('updated', $model, $old, $new);
    }

    /**
     * Convenience: log deletion.
     */
    public function deleted(Model $model): AuditEntry
    {
        return $this->log('deleted', $model, $model->getAttributes());
    }
}
