<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Relationship: string implements HasLabel
{
    case Father = 'father';

    case Mother = 'mother';

    case Son = 'son';

    case Daughter = 'daughter';

    case Husband = 'husband';

    case Wife = 'wife';

    case Brother = 'brother';

    case Sister = 'sister';

    case Grandfather = 'grandfather';

    case Grandmother = 'grandmother';

    case Grandson = 'grandson';

    case Granddaughter = 'granddaughter';

    case Uncle = 'uncle';

    case Aunt = 'aunt';

    case Nephew = 'nephew';

    case Niece = 'niece';

    case Cousin = 'cousin';

    case AdoptedChild = 'adopted_child';

    case Other = 'other';

    /**
     * @return array<string, string>
     */
    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }

        return $options;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Father => __('Father'),
            self::Mother => __('Mother'),
            self::Son => __('Son'),
            self::Daughter => __('Daughter'),
            self::Husband => __('Husband'),
            self::Wife => __('Wife'),
            self::Brother => __('Brother'),
            self::Sister => __('Sister'),
            self::Grandfather => __('Grandfather'),
            self::Grandmother => __('Grandmother'),
            self::Grandson => __('Grandson'),
            self::Granddaughter => __('Granddaughter'),
            self::Uncle => __('Uncle'),
            self::Aunt => __('Aunt'),
            self::Nephew => __('Nephew'),
            self::Niece => __('Niece'),
            self::Cousin => __('Cousin'),
            self::AdoptedChild => __('Adopted Child'),
            self::Other => __('Other'),
        };
    }
}
