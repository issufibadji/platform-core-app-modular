<?php

namespace Modules\FeatureFlags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureFlag extends Model
{
    protected $fillable = [
        'key',
        'description',
        'organization_id',
        'module',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeGlobal($query)
    {
        return $query->whereNull('organization_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            \Modules\Organizations\Models\Organization::class,
            'organization_id'
        );
    }
}
