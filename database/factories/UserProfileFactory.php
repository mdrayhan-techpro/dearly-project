<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nickname' => 'User_' . fake()->unique()->lexify('??????'),
            'age' => fake()->numberBetween(20, 65),
            'gender' => fake()->randomElement(['female', 'male', 'non_binary', 'prefer_not_to_say']),
            'preferred_language' => 'en',
            'country' => fake()->country(),
            'bio' => fake()->paragraph(),
            'interests' => ['Reading', 'Astronomy', 'Gardening'],
            'communication_preferences' => ['cadence' => 'weekly'],
            'is_onboarded' => true,
            'is_matching_paused' => false,
        ];
    }
}
