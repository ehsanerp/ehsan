<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Members\Tables;

use App\Filament\Admin\Resources\Members\MemberResource;
use App\Models\Member;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('membership_status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label(__('Branch'))
                    ->badge()
                    ->toggleable(),
                TextColumn::make('parent.name')
                    ->label(__('Family'))
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->url(fn (?Member $record): ?string => $record?->parent ? MemberResource::getUrl('view', ['record' => $record->parent, 'tenant' => filament()->getTenant()]) : null)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Last updated'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
