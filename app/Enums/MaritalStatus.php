<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MaritalStatus: string implements HasLabel
{
    case Single = 'single';

    case Married = 'married';

    public function getLabel(): string
    {
        return match ($this) {
            self::Single => __('Single'),
            self::Married => __('Married'),
        };
    }
}
