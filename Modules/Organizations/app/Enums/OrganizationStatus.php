<?php

namespace Modules\Organizations\Enums;

enum OrganizationStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';

    public function label(): string
    {
        return match($this) {
            self::Active    => 'Active',
            self::Suspended => 'Suspended',
            self::Archived  => 'Archived',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active    => 'green',
            self::Suspended => 'yellow',
            self::Archived  => 'zinc',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
