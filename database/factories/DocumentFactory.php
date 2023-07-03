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
        $type = fake()->randomElement(DocumentType::values());

        $signed_at = $expires_at = null;

        if (DocumentType::protocol->is($type)) {
            $signed_at = CarbonImmutable::createFromInterface(fake()->dateTimeBetween('-1 year', 'now'));
            $expires_at = $signed_at->addDays(fake()->randomNumber(2));
        }

        return [
            'name' => fake()->sentence(),
            'type' => $type,
            'signed_at' => $signed_at?->toDateString(),
            'expires_at' => $expires_at?->toDateString(),
        ];
    }
}
