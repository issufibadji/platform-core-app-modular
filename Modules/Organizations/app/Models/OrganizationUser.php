<?php

namespace Modules\Organizations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUser extends Pivot
{
    protected $table = 'organization_user';

    public $timestamps = true;

    protected $fillable = [
        'organization_id',
        'user_id',
        'is_owner',
        'status',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'is_owner'  => 'boolean',
            'joined_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
