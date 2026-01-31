<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Members;

use App\Filament\Admin\Resources\Members\Pages\Contact;
use App\Filament\Admin\Resources\Members\Pages\CreateMember;
use App\Filament\Admin\Resources\Members\Pages\EditMember;
use App\Filament\Admin\Resources\Members\Pages\Identification;
use App\Filament\Admin\Resources\Members\Pages\ListMembers;
use App\Filament\Admin\Resources\Members\Pages\Residential;
use App\Filament\Admin\Resources\Members\Pages\ViewMember;
use App\Filament\Admin\Resources\Members\Schemas\MemberForm;
use App\Filament\Admin\Resources\Members\Schemas\MemberInfolist;
use App\Filament\Admin\Resources\Members\Tables\MembersTable;
use App\Models\Member;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;

final class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return MemberForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return MemberInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return MembersTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'view' => ViewMember::route('/{record}'),
            'edit' => EditMember::route('/{record}/edit'),
            'identification' => Identification::route('/{record}/identification'),
            'residential' => Residential::route('/{record}/residential'),
            'contact' => Contact::route('/{record}/contact'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewMember::class,
            EditMember::class,
            Identification::class,
            Residential::class,
            Contact::class,
        ]);
    }

    /**
     * @return Builder<Member>
     */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
