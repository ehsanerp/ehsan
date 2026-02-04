<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\IdentificationType;
use App\Enums\MaritalStatus;
use App\Enums\MembershipStatus;
use App\Enums\MemberType;
use App\Models\Branch;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
final class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'identity_number' => fake()->unique()->numerify('###########'),
            'identification_type' => IdentificationType::MyKad->value,
            'residential_address' => fake()->address(),
            'residence_since' => fake()->dateTimeBetween('-10 years', '-1 years'),
            'address_on_identity_card' => fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'phone_no' => '+60 '.fake()->randomElement(['12', '13', '14', '16', '17', '18', '19']).'-'.fake()->numerify('###').' '.fake()->numerify('####'),
            'gender' => Gender::cases()[array_rand(Gender::cases())]->value,
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-10 years'),
            'marital_status' => MaritalStatus::cases()[array_rand(MaritalStatus::cases())]->value,
            'member_type' => MemberType::cases()[array_rand(MemberType::cases())]->value,
            'membership_status' => fake()->randomElement(collect(MembershipStatus::cases())->except(3)->all()),
            'branch_id' => Branch::factory(),
        ];
    }
}
