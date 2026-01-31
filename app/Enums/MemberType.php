<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MemberType: string implements HasLabel
{
    case Resident = 'resident';

    case Settled = 'settled';

    case NonCitizen = 'non-citizen';

    public function getLabel(): string
    {
        return match ($this) {
            self::Resident => __('Resident'),
            self::Settled => __('Settled'),
            self::NonCitizen => __('Non-Citizen'),
        };
    }
}
