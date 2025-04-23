<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\NewsStatus;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'status' => fake()->randomElement(NewsStatus::cases()),
            'organisation_id' => Organisation::factory(),

        ];
    }


}
