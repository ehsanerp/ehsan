<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

final class GeneralSettings extends Settings
{
    public string $brandName;

    public ?string $brandLogo = null;

    public static function group(): string
    {
        return 'general';
    }
}
