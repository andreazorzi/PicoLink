<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\Short;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class VisitFactory extends Factory
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
            'url_id' => Url::factory(),
            'language' => fake()->languageCode(),
            'device' => fake()->randomElement(['mobile', 'tablet', 'desktop']),
            // 'country' => Str::lower(fake()->countryCode()),
            'country' => fake()->randomElement(['it', 'de', 'gb', 'fr']),
            'referrer' => fake()->randomElement(['facebook.com', 'google.com', 'bing.com', 'x.com', 'youtube.com']),
            'created_at' => date("Y-m-d H:i:s", rand(strtotime("-7 days"), strtotime("today"))),
        ];
    }
}
