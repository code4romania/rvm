<?php

declare(strict_types=1);

namespace Database\Factories\Organisation;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organisation\ResourceType>
 */
class ResourceTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $element = collect(['Program de pregatire scolara', 'Interventie rapida', 'Voluntari specializati']);

        return [
            'name'=> fake()->randomElement($element),
        ];
    }
}
