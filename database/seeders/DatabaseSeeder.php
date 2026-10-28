<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete foundational data.
     */
    public function run(): void
    {
        $seeders = [
            StationerySeeder::class,
            QuestionnaireSeeder::class,
            QuestionnaireTranslationSeeder::class,
            StationeryAndMailTranslationSeeder::class,
            ApplicationSettingSeeder::class,
        ];

        // Seed demo personas and default test admin ONLY in local development or automated testing
        if (app()->environment('local', 'testing')) {
            $seeders[] = AdminUserSeeder::class;
        }

        $this->call($seeders);
    }
}
