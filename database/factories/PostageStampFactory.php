<?php

namespace Database\Factories;

use App\Models\PostageStamp;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostageStampFactory extends Factory
{
    protected $model = PostageStamp::class;

    public function definition(): array
    {
        return [
            'name' => 'Botanical Fern ' . fake()->word(),
            'slug' => fake()->unique()->slug(),
            'color' => '#3E5C46',
            'icon' => 'leaf',
            'is_active' => true,
        ];
    }
}
