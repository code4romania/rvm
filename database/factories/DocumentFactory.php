<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\DocumentType;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence(),
            'type' => DocumentType::other,
        ];
    }

    public function protocol(): static
    {
        return $this->state(function (array $attributes) {
            $signed_at = CarbonImmutable::createFromInterface(fake()->dateTimeBetween('-1 year', '-1 week'));
            $expires_at = fake()->boolean()
                ? today()->addDays(30)
                : today();

            return [
                'type' => DocumentType::protocol,
                'signed_at' => $signed_at->toDateString(),
                'expires_at' => $expires_at->toDateString(),
            ];
        });
    }

    public function contract()
    {
        return $this->state([
            'type' => DocumentType::contract,
        ]);
    }
}
