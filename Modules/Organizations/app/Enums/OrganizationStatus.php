<?php

namespace Modules\Organizations\Enums;

enum OrganizationStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::Active    => 'Active',
            self::Inactive  => 'Inactive',
            self::Suspended => 'Suspended',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active    => 'green',
            self::Inactive  => 'zinc',
            self::Suspended => 'red',
        };
    }
}
