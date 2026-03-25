<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Organizations\Models\Organization;

class Setting extends Model
{
    protected $fillable = [
        'organization_id',
        'module',
        'key',
        'value',
        'type',
        'group',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // ---------------------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------------------

    public function scopeGlobal($query)
    {
        return $query->whereNull('organization_id');
    }

    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeForGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // ---------------------------------------------------------------------------
    // Typed value accessor
    // ---------------------------------------------------------------------------

    public function typedValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float'   => (float) $this->value,
            'json'    => json_decode($this->value, true),
            default   => $this->value,
        };
    }
}
