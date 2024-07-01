<?php

namespace Database\Factories;

use App\Models\TagCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $background_color = fake()->hexColor();
        $r = hexdec(substr($background_color, 1, 2));
        $g = hexdec(substr($background_color, 3, 2));
        $b = hexdec(substr($background_color, 5, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        $text_color = ($yiq >= 128) ? '#000000' : '#ffffff';
        
        return [
            'name' => ucwords(fake()->unique()->word()),
            'tag_category_id' => TagCategory::factory(),
            'background_color' => $background_color,
            'text_color' => $text_color,
        ];
    }
}
