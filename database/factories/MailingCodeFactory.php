<?php

namespace Database\Factories;

use App\Models\MailingCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MailingCodeFactory extends Factory
{
    protected $model = MailingCode::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'code' => 'DEARLY-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'is_active' => true,
            'permissions_policy' => 'anyone',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
