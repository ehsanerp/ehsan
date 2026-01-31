<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MembershipStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';

    case Active = 'active';

    case Inactive = 'inactive';

    case Terminated = 'terminated';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Active => __('Active'),
            self::Inactive => __('Inactive'),
            self::Terminated => __('Terminated'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Active => 'success',
            self::Inactive => 'warning',
            self::Terminated => 'danger',
        };
    }
}
