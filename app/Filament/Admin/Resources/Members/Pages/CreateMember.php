<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Members\Pages;

use App\Filament\Admin\Resources\Members\MemberResource;
use App\Filament\Admin\Resources\Members\Schemas\MemberForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;

final class CreateMember extends CreateRecord
{
    use HasWizard;

    protected static string $resource = MemberResource::class;

    /** @return array<int, Step> */
    protected function getSteps(): array
    {
        return [
            Step::make('personal')
                ->label(__('Personal'))
                ->inlineLabel()
                ->schema([
                    Section::make(__('Personal Information'))
                        ->components(MemberForm::getPersonalInformationSchema()),
                ]),
            Step::make('identification')
                ->label(__('Identification'))
                ->inlineLabel()
                ->schema([
                    Section::make(__('Identification Details'))
                        ->components(MemberForm::getIdentityInformationSchema()),
                ]),
            Step::make('residence')
                ->label(__('Residence'))
                ->inlineLabel()
                ->schema([
                    Section::make(__('Residence Information'))
                        ->components(MemberForm::getResidentialAddressSchema()),
                ]),
            Step::make('contact')
                ->label(__('Contact'))
                ->inlineLabel()
                ->schema([
                    Section::make(__('Contact Information'))
                        ->components(MemberForm::getContactInformationSchema()),
                ]),
        ];
    }
}
