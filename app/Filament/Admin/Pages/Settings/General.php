<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Settings;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;
use UnitEnum;

final class General extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static string $settings = GeneralSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings/general';

    #[Override]
    public function getHeading(): string
    {
        return __('General Settings');
    }

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Application Settings'))
                    ->description(__('Configure general settings for the application.'))
                    ->inlineLabel()
                    ->components([
                        TextInput::make('brandName')
                            ->label(__('Brand name'))
                            ->required()
                            ->maxLength(20)
                            ->hint(__('Set the brand name for the application.')),
                        FileUpload::make('brandLogo')
                            ->label(__('Brand logo'))
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->directory('brand')
                            ->disk(fn (): mixed => config('filesystems.default'))
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->hint(__('Upload your organization logo. If set, it will be used as the brand logo on the login page.'))
                            ->helperText(__('Recommended size: 200x60px or larger. Supported formats: JPEG, PNG, WebP, SVG')),
                    ]),
            ]);
    }
}
