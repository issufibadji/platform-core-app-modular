<?php

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUser extends Pivot
{
    protected $table = 'organization_user';

    public $incrementing = true;

    protected function casts(): array
    {
        return [
            'is_owner'  => 'boolean',
            'joined_at' => 'datetime',
        ];
    }
}
