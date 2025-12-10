<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;

abstract class TestCase extends BaseTestCase
{
    public User $user;

    public Branch $branch;

    protected $seed = true;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        activity()->disableLogging();
    }

    final public function asUser(): self
    {
        $this->user = User::factory()->create()
            ->assignRole('admin');
        $this->branch = Branch::factory()->primary()->create();
        $this->user->allowedBranches()->attach($this->branch);
        $this->actingAs($this->user);
        filament()->setTenant($this->branch);

        return $this;
    }
}
