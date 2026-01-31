<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Members\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class MemberInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Member Details'))
                    ->columns()
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('Full name')),
                        TextEntry::make('residential_address')
                            ->label(__('Residential address'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('date_of_birth')
                            ->label(__('Date of birth'))
                            ->placeholder('-')
                            ->date(),
                        TextEntry::make('gender')
                            ->label(__('Gender'))
                            ->placeholder('-'),
                        TextEntry::make('email')
                            ->label(__('Email address'))
                            ->placeholder('-'),
                        TextEntry::make('phone_no')
                            ->label(__('Phone number'))
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
