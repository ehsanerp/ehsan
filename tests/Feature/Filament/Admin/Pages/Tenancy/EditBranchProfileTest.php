<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Tenancy\EditBranchProfile;

use function Pest\Livewire\livewire;

it('can render the page', function (): void {
    livewire(EditBranchProfile::class)
        ->assertSuccessful();
});

it('can update the branch', function (): void {
    livewire(EditBranchProfile::class)
        ->fillForm([
            'name' => 'Updated Branch Name',
            'address' => '123 Updated Street',
            'phone_no' => '555-1234',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($this->branch->fresh())
        ->name->toBe('Updated Branch Name')
        ->address->toBe('123 Updated Street')
        ->phone_no->toBe('555-1234');
});
