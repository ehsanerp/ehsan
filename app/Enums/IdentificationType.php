<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum IdentificationType: string implements HasLabel
{
    case MyKad = 'mykad';

    case PoliceArmy = 'police_army';

    case Passport = 'passport';

    public function getLabel(): string
    {
        return match ($this) {
            self::MyKad => __('MyKad'),
            self::PoliceArmy => __('Police/Army'),
            self::Passport => __('Passport'),
        };
    }
}
