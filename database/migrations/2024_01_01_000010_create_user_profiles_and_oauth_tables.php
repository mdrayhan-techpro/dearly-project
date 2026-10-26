<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nickname', 100)->unique();
            $table->unsignedInteger('age');
            $table->string('gender', 50)->nullable();
            $table->string('preferred_language', 10)->default('en');
            $table->string('country', 100)->nullable();
            $table->text('bio')->nullable();
            $table->json('interests')->nullable();
            $table->json('communication_preferences')->nullable();
            $table->boolean('is_matching_paused')->default(false);
            $table->string('profile_visibility', 50)->default('public');
            $table->boolean('is_onboarded')->default(false);
            $table->timestamps();
        });

        Schema::create('oauth_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider', 50);
            $table->string('provider_user_id', 255);
            $table->timestamps();

            $table->unique(['provider', 'provider_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_accounts');
        Schema::dropIfExists('user_profiles');
    }
};
