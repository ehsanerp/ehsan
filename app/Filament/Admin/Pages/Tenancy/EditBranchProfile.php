<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Tenancy;

use App\Filament\Forms\Components\PhoneInput;
use App\Models\Branch;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Override;

final class EditBranchProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return __('Edit Branch');
    }

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Branch Profile'))
                    ->description(__('Fill in the basic branch information.'))
                    ->inlineLabel()
                    ->components([
                        SpatieMediaLibraryFileUpload::make('branch_logo')
                            ->label(__('Branch Logo'))
                            ->collection('branch_logo')
                            ->image()
                            ->avatar(),
                        TextInput::make('name')
                            ->label(__('Branch Name'))
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_primary')
                            ->label(__('Primary Branch'))
                            ->hint(__('Primary branch can see all members across all branches.'))
                            ->default(false)
                            ->disabled(fn (Branch $record) => $record->is_primary),
                        Textarea::make('address')
                            ->label(__('Address'))
                            ->rows(3),
                        PhoneInput::make('phone_no')
                            ->label(__('Phone number')),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->record(filament()->getTenant())
                ->disabled(fn (Branch $record) => $record->is_primary)
                ->successRedirectUrl(url('/admin')),
        ];
    }
}
