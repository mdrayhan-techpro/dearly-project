<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->unsignedInteger('length'); // 20, 30, 40, 50
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->nullable()->constrained('questionnaires')->nullOnDelete();
            $table->string('dimension', 100);
            $table->text('question_text');
            $table->decimal('weight', 4, 2)->default(1.00);
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('option_key', 10); // A, B, C, D, E
            $table->text('option_text');
            $table->integer('score_value')->default(1);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('user_questionnaire_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->cascadeOnDelete();
            $table->unsignedInteger('questionnaire_length');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('user_questionnaire_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->constrained('question_options')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });

        Schema::create('match_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('matched_user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('compatibility_score', 5, 2); // e.g. 92.50
            $table->json('shared_dimensions')->nullable();
            $table->boolean('is_notified')->default(false);
            $table->string('status', 50)->default('active'); // active, connected, dismissed
            $table->timestamps();

            $table->unique(['user_id', 'matched_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_results');
        Schema::dropIfExists('questionnaire_answers');
        Schema::dropIfExists('user_questionnaire_attempts');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('questionnaires');
    }
};
