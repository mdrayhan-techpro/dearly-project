<?php

namespace Database\Factories;

use App\Models\StationeryTheme;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationeryThemeFactory extends Factory
{
    protected $model = StationeryTheme::class;

    public function definition(): array
    {
        return [
            'name' => 'Vintage Parchment ' . fake()->word(),
            'slug' => fake()->unique()->slug(),
            'bg_color' => '#FDFBF7',
            'border_color' => '#D6C7AE',
            'font_family' => 'font-serif',
            'is_active' => true,
        ];
    }
}
