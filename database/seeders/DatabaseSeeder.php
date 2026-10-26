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
        $this->call([
            StationerySeeder::class,
            QuestionnaireSeeder::class,
            ApplicationSettingSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
