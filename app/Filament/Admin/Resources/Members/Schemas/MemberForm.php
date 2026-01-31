<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Members\Schemas;

use App\Enums\Gender;
use App\Enums\IdentificationType;
use App\Enums\MaritalStatus;
use App\Enums\MemberType;
use App\Filament\Admin\Resources\Members\Pages\Contact;
use App\Filament\Admin\Resources\Members\Pages\EditMember;
use App\Filament\Admin\Resources\Members\Pages\Identification;
use App\Filament\Admin\Resources\Members\Pages\Residential;
use App\Filament\Forms\Components\PhoneInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

final class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Personal Information'))
                    ->schema(self::getPersonalInformationSchema())
                    ->visibleOn(EditMember::class),
                Section::make(__('Identity Information'))
                    ->schema(self::getIdentityInformationSchema())
                    ->visibleOn(Identification::class),
                Section::make(__('Residential Address'))
                    ->schema(self::getResidentialAddressSchema())
                    ->visibleOn(Residential::class),
                Section::make(__('Contact Information'))
                    ->schema(self::getContactInformationSchema())
                    ->visibleOn(Contact::class),
            ]);
    }

    /**
     * @return array<Htmlable|string>
     */
    public static function getPersonalInformationSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('Full name'))
                ->required(),
            Radio::make('gender')
                ->label(__('Gender'))
                ->options(Gender::class),
            Radio::make('marital_status')
                ->label(__('Marital Status'))
                ->options(MaritalStatus::class),
            DatePicker::make('date_of_birth')
                ->label(__('Date of Birth'))
                ->closeOnDateSelection()
                ->maxDate(now())
                ->placeholder('dd/mm/yyyy'),
        ];
    }

    /**
     * @return array<Htmlable|string>
     */
    public static function getIdentityInformationSchema(): array
    {
        return [
            TextInput::make('identity_number')
                ->label(__('Identity card number'))
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(20)
                ->mask(fn (Get $get): string => match ($get('identification_type')) {
                    IdentificationType::MyKad => '999999999999',
                    IdentificationType::PoliceArmy => 'a**9999999999',
                    IdentificationType::Passport => '**** **** **** ****',
                    default => '',
                }),
            Radio::make('identification_type')
                ->label(__('Identification Type'))
                ->options(IdentificationType::class)
                ->default('mykad')
                ->required()
                ->live()
                ->inline(),
            SpatieMediaLibraryFileUpload::make('identity_document')
                ->label(__('Photo of Identity Document'))
                ->collection('identity_documents')
                ->conversion('standard')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                ->image()
                ->rules(['dimensions:max_width=1200,max_height=800'])
                ->imageEditor()
                ->imageEditorAspectRatioOptions([
                    '1:1',
                    '3:2',
                ])
                ->maxSize(5120)
                ->hint(__('Upload a clear image of your identity document (MyKad, Passport, etc.) for identity verification purposes.'))
                ->helperText(__('The image file must not larger than 5MB and maximum resolution is 1200x800px.'))
                ->columnSpanFull(),
            Textarea::make('address_on_identity_card')
                ->label(__('Address on Identity Card'))
                ->rows(3),
        ];
    }

    /**
     * @return array<Htmlable|string>
     */
    public static function getResidentialAddressSchema(): array
    {
        return [
            Radio::make('member_type')
                ->label(__('Member Type'))
                ->options(MemberType::class)
                ->required()
                ->inline(),
            Textarea::make('residential_address')
                ->label(__('Residential Address'))
                ->rows(3)
                ->required(),
            DateTimePicker::make('residence_since')
                ->label(__('Residence Since'))
                ->closeOnDateSelection()
                ->time(false)
                ->required(),
            SpatieMediaLibraryFileUpload::make('residential_address_document')
                ->label(__('Proof of Residential Address'))
                ->collection('residential_address_documents')
                ->conversion('standard')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                ->image()
                ->rules(['dimensions:max_width=800,max_height=1200'])
                ->imageEditor()
                ->imageEditorAspectRatioOptions([
                    '1:1',
                    '2:3',
                ])
                ->maxSize(5120)
                ->hint(__('For address verification, upload a clear image of a valid utility bill, internet bill or rental agreement showing residential address.'))
                ->helperText(__('The image file must not larger than 5MB and maximum resolution is 800x1200px.'))
                ->columnSpanFull(),
            Select::make('branch_id')
                ->relationship('branch', 'name')
                ->label(__('Branch'))
                ->searchable()
                ->required()
                ->preload(),
        ];
    }

    /**
     * @return array<Htmlable|string>
     */
    public static function getContactInformationSchema(): array
    {
        return [
            TextInput::make('email')
                ->label(__('Email address'))
                ->email()
                ->unique(ignoreRecord: true),
            PhoneInput::make('phone_no')
                ->label(__('Phone number')),
        ];
    }
}
