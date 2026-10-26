<?php

namespace Database\Factories;

use App\Models\Letter;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\User;
use App\Models\WaxSeal;
use Illuminate\Database\Eloquent\Factories\Factory;

class LetterFactory extends Factory
{
    protected $model = Letter::class;

    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),
            'stationery_theme_id' => StationeryTheme::factory(),
            'postage_stamp_id' => PostageStamp::factory(),
            'wax_seal_id' => WaxSeal::factory(),
            'title' => fake()->sentence(4),
            'greeting' => 'Dearest Friend,',
            'body' => fake()->paragraphs(3, true),
            'signoff' => 'Yours in ink,',
            'status' => 'delivered',
            'is_read' => false,
            'sent_at' => now(),
            'delivered_at' => now(),
            'sender_deleted' => false,
            'recipient_deleted' => false,
        ];
    }
}
