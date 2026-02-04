<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Models\Branch;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

final class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::factory(8)
            ->state(new Sequence(fn (): array => [
                'name' => 'Surau Al-Hidayah',
            ], [
                'name' => 'Surau INSMAT',
            ], [
                'name' => 'Surau Al-Ikhlas',
            ], [
                'name' => 'Surau Al-Firdaus',
            ], [
                'name' => 'Surau Al-Hijrah',
            ], [
                'name' => 'Surau Al-Falah',
            ], [
                'name' => 'Surau Al-Ukasyah',
            ], [
                'name' => 'Surau Al-Ukhwatul Hasanah',
            ]))
            ->has(Member::factory(10)
                ->state(new Sequence(fn (): array => [
                    'membership_status' => fake()->randomElement(collect(MembershipStatus::cases())->except(3)->toArray()),
                ])))
            ->create();
    }
}
