<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages\Tenancy;

use App\Models\Branch;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Override;

final class CreateBranch extends RegisterTenant
{
    public static function getLabel(): string
    {
        return __('Create Branch');
    }

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label(__('Branch Name'))
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_primary')
                    ->label(__('Primary Branch'))
                    ->helperText(__('Primary branches can see all members across all branches.'))
                    ->default(false),
            ]);
    }

    #[Override]
    protected function handleRegistration(array $data): Branch
    {
        $branch = Branch::query()->create($data);

        // Associate the current user with the new branch
        $branch->users()->attach(auth()->user());

        return $branch;
    }

    #[Override]
    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    #[Override]
    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction(),
            Action::make('cancel')
                ->label(__('Cancel'))
                ->url(url('/admin'))
                ->color('gray'),
        ];
    }
}
