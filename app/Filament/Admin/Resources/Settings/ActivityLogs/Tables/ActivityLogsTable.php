<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\ActivityLogs\Tables;

use App\Filament\Admin\Resources\Settings\Users\UserResource;
use App\Models\Activity;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordAction('view')
            ->columns([
                TextColumn::make('log_name')
                    ->label(__('Log Name'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('resource')
                    ->label(__('Resource'))
                    ->getStateUsing(fn (Activity $record): string => class_basename($record->subject_type ?? ''))
                    ->badge(),
                TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->formatStateUsing(function (Activity $record): string {
                        if (! $record->subject) {
                            return __('N/A');
                        }

                        // Handle different model types with different name attributes
                        $name = $record->subject->getAttribute('name');

                        return is_scalar($name) ? (string) $name : __('Unknown');
                    })
                    ->url(function (Activity $record): ?string {
                        $subject = $record->subject;
                        if (! $subject) {
                            return null;
                        }

                        return match ($record->subject_type) {
                            User::class => UserResource::getUrl('view', ['record' => $subject]),
                            default => null,
                        };
                    })
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('causer')
                    ->label(__('Performed By'))
                    ->placeholder(__('System'))
                    ->formatStateUsing(function (Activity $record): string {
                        if (! $record->causer) {
                            return __('System');
                        }

                        $name = $record->causer->getAttribute('name');

                        return is_scalar($name) ? (string) $name : __('Unknown');
                    })
                    ->url(function (Activity $record): ?string {
                        if (! $record->causer) {
                            return null;
                        }

                        $causer = $record->causer;

                        return match ($record->causer_type) {
                            User::class => UserResource::getUrl('view', ['record' => $causer]),
                            default => null,
                        };
                    })
                    ->openUrlInNewTab()
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->sortable()
                    ->toggleable()
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('Log Name'))
                    ->options([
                        'default' => __('Default'),
                    ]),
                SelectFilter::make('subject_type')
                    ->options([
                        User::class => __('User'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
