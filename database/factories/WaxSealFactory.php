<?php

namespace Database\Factories;

use App\Models\WaxSeal;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaxSealFactory extends Factory
{
    protected $model = WaxSeal::class;

    public function definition(): array
    {
        return [
            'name' => 'Burgundy Crest ' . fake()->word(),
            'slug' => fake()->unique()->slug(),
            'color' => '#783C46',
            'symbol' => 'feather-pointed',
            'is_active' => true,
        ];
    }
}
