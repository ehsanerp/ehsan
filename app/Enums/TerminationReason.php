<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TerminationReason: string implements HasColor, HasLabel
{
    case MovingHouse = 'moving_house';

    case Death = 'death';

    case VoluntaryWithdrawal = 'voluntary_withdrawal';

    case DuplicateRecord = 'duplicate_record';

    case InactiveMembership = 'inactive_membership';

    case Misconduct = 'misconduct';

    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::MovingHouse => __('Moving House'),
            self::Death => __('Death'),
            self::VoluntaryWithdrawal => __('Voluntary Withdrawal'),
            self::DuplicateRecord => __('Duplicate Record'),
            self::InactiveMembership => __('Inactive Membership'),
            self::Misconduct => __('Misconduct'),
            self::Other => __('Other'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::MovingHouse => 'info',
            self::Death => 'gray',
            self::VoluntaryWithdrawal => 'warning',
            self::DuplicateRecord => 'danger',
            self::InactiveMembership => 'warning',
            self::Misconduct => 'danger',
            self::Other => 'gray',
        };
    }
}
