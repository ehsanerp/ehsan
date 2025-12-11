<?php

declare(strict_types=1);

use App\Filament\Admin\Pages\Tenancy\CreateBranch;
use App\Models\Branch;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('can render the page', function (): void {
    livewire(CreateBranch::class)
        ->assertSuccessful();
});

it('can create a branch', function (): void {
    $branch = Branch::factory()->make();
    livewire(CreateBranch::class)
        ->fillForm([
            'name' => $branch->name,
            'email' => $branch->email,
            'phone_no' => $branch->phone_no,
            'address' => $branch->address,
        ])
        ->call('register')
        ->assertHasNoFormErrors();
    assertDatabaseHas(Branch::class, [
        'name' => $branch->name,
    ]);
});

it('can create a primary branch', function (): void {
    $primaryBranch = Branch::factory()->primary()->create();
    $newPrimaryBranch = Branch::factory()->primary()->make();
    livewire(CreateBranch::class)
        ->fillForm([
            'name' => $newPrimaryBranch->name,
            'is_primary' => true,
            'email' => $newPrimaryBranch->email,
            'phone_no' => $newPrimaryBranch->phone_no,
            'address' => $newPrimaryBranch->address,
        ])
        ->call('register')
        ->assertHasNoFormErrors();
    expect($primaryBranch->fresh())
        ->is_primary->toBe(false);
});
