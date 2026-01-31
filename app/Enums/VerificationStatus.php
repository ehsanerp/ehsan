<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VerificationStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';

    case Verified = 'verified';

    case Unverified = 'unverified';

    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('Pending Review'),
            self::Verified => __('Verified'),
            self::Unverified => __('Unverified'),
            self::Rejected => __('Rejected'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Verified => 'success',
            self::Unverified => 'warning',
            self::Rejected => 'danger',
        };
    }
}
