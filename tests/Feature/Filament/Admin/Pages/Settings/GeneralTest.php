<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Settings\General;
use App\Settings\GeneralSettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render the page', function (): void {
    livewire(General::class)
        ->assertSuccessful();
});

it('can open the route', function (): void {
    get(route('filament.admin.pages.settings.general'))
        ->assertSuccessful();
});

it('can update brand name', function (): void {
    livewire(General::class)
        ->fillForm([
            'brandName' => 'New Brand Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(resolve(GeneralSettings::class))
        ->brandName->toBe('New Brand Name');
});

it('can update brand logo', function (): void {
    Storage::fake('public');
    livewire(General::class)
        ->fillForm([
            'brandLogo' => UploadedFile::fake()->image('brand-logo.jpg'),
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(resolve(GeneralSettings::class))
        ->brandLogo->not->toBeEmpty();
});
