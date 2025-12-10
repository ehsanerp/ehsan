<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Settings\ActivityLogs\Schemas;

use App\Filament\Admin\Resources\Settings\Roles\RoleResource;
use App\Filament\Admin\Resources\Settings\Users\UserResource;
use App\Models\Activity;
use App\Models\Role;
use App\Models\User;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Activity Details'))
                    ->schema([
                        TextEntry::make('log_name')
                            ->label(__('Log Name'))
                            ->badge(),
                        TextEntry::make('event')
                            ->label(__('Operation')),
                        TextEntry::make('resource')
                            ->label(__('Resource'))
                            ->getStateUsing(fn (Activity $record): string => match ($record->subject_type) {
                                User::class => UserResource::getModelLabel(),
                                Role::class => RoleResource::getModelLabel(),
                                default => class_basename($record->subject_type ?? ''),
                            }),
                        TextEntry::make('subject')
                            ->label(__('Subject'))
                            ->icon(Heroicon::ArrowTopRightOnSquare)
                            ->formatStateUsing(function (Activity $record): mixed {
                                if (! $record->subject) {
                                    return __('N/A');
                                }

                                // Handle different model types with different name attributes
                                return match ($record->subject_type) {
                                    User::class => $record->subject->getAttribute('name'),
                                    Role::class => $record->subject->getAttribute('display_name'),
                                    default => __('Unknown'),
                                };
                            })
                            ->url(function (Activity $record): ?string {
                                $subject = $record->subject;
                                if (! $subject) {
                                    return null;
                                }

                                return match ($record->subject_type) {
                                    User::class => UserResource::getUrl('view', ['record' => $subject]),
                                    Role::class => RoleResource::getUrl('view', ['record' => $subject]),
                                    default => null,
                                };
                            })
                            ->openUrlInNewTab(),
                        TextEntry::make('causer')
                            ->label(__('Performed By'))
                            ->placeholder(__('System'))
                            ->icon(Heroicon::ArrowTopRightOnSquare)
                            ->formatStateUsing(function (Activity $record): mixed {
                                if (! $record->causer) {
                                    return __('System');
                                }

                                // Handle different model types with different name attributes
                                return match ($record->causer_type) {
                                    User::class => $record->causer->getAttribute('name'),
                                    Role::class => $record->causer->getAttribute('display_name'),
                                    default => __('Unknown'),
                                };
                            })
                            ->url(function (Activity $record): ?string {
                                if (! $record->causer) {
                                    return null;
                                }

                                $causer = $record->causer;

                                return match ($record->causer_type) {
                                    User::class => UserResource::getUrl('view', ['record' => $causer]),
                                    Role::class => RoleResource::getUrl('view', ['record' => $causer]),
                                    default => null,
                                };
                            })
                            ->openUrlInNewTab(),
                        TextEntry::make('created_at')
                            ->label(__('Created At'))
                            ->dateTime(),
                        TextEntry::make('description')
                            ->label(__('Description'))
                            ->columnSpan(2),
                    ])
                    ->columns(2),
                Section::make(__('Properties'))
                    ->schema([
                        KeyValueEntry::make('properties.attributes')
                            ->label(__('New Values'))
                            ->keyLabel(__('Field'))
                            ->valueLabel(__('Value'))
                            ->visible(fn (Activity $record): bool => ! empty($record->properties['attributes'] ?? []))
                            ->formatStateUsing(function (mixed $state): array {
                                if (empty($state)) {
                                    return [];
                                }

                                if (! is_array($state)) {
                                    return [];
                                }

                                return collect($state)
                                    ->mapWithKeys(fn (mixed $value, string $key): array => [
                                        ucfirst(str_replace('_', ' ', $key)) => is_scalar($value) ? (string) $value : (json_encode($value) ?: __('Invalid data')),
                                    ])
                                    ->all();
                            }),
                        KeyValueEntry::make('properties.old')
                            ->label(__('Old Values'))
                            ->keyLabel(__('Field'))
                            ->valueLabel(__('Value'))
                            ->visible(fn (Activity $record): bool => ! empty($record->properties['old'] ?? []))
                            ->formatStateUsing(function (mixed $state): array {
                                if (empty($state)) {
                                    return [];
                                }

                                if (! is_array($state)) {
                                    return [];
                                }

                                return collect($state)
                                    ->mapWithKeys(fn (mixed $value, string $key): array => [
                                        ucfirst(str_replace('_', ' ', $key)) => is_scalar($value) ? (string) $value : (json_encode($value) ?: __('Invalid data')),
                                    ])
                                    ->all();
                            }),
                        TextEntry::make('properties')
                            ->label(__('All Properties'))
                            ->visible(fn (Activity $record): bool => empty($record->properties['attributes'] ?? []) && empty($record->properties['old'] ?? []) && ! empty($record->properties))
                            ->formatStateUsing(function (mixed $state): string {
                                if (empty($state)) {
                                    return __('No changes recorded');
                                }

                                if (is_array($state)) {
                                    return collect($state)
                                        ->map(fn (mixed $value, string $key): string => $key.': '.(is_scalar($value) ? (string) $value : (json_encode($value) ?: __('Invalid data'))))
                                        ->join(', ');
                                }

                                return is_scalar($state) ? (string) $state : (json_encode($state) ?: __('Invalid data'));
                            }),
                    ]),
            ]);
    }
}
