<?php

namespace Database\Factories;

use App\Models\Short;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_id' => Short::factory(),
            'url' => "https://www.google.com/search?q=".fake()->word(),
            'language' => fake()->languageCode(),
        ];
    }
}
