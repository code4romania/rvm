<?php

declare(strict_types=1);

namespace Database\Factories\Organisation;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organisation\RiskCategory>
 */
class RiskCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $risks = collect(['Categorie de risc 1', 'Categorie de risc 2', 'Categorie de risc 3', 'Categorie de risc 4']);

        return [
            'name' => fake()->randomElement($risks),
        ];
    }
}
