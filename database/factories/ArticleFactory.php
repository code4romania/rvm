<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enum\ArticleStatus;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
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
            'status' => fake()->randomElement(ArticleStatus::cases()),
            'organisation_id' => Organisation::factory(),

        ];
    }


}
