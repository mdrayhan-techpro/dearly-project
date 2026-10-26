<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stationery_themes', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 255);
            $table->string('background_css', 255);
            $table->string('text_color', 50)->default('#342A25');
            $table->string('border_style', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('postage_stamps', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 255);
            $table->string('theme_category', 100)->default('vintage');
            $table->string('image_svg_path', 255);
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('wax_seals', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 255);
            $table->string('color_hex', 50)->default('#783C46');
            $table->text('symbol_svg')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('letter_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_one_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('participant_two_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_letter_at')->nullable();
            $table->timestamps();
        });

        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->nullable()->constrained('letter_threads')->nullOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('reply_to_letter_id')->nullable()->constrained('letters')->nullOnDelete();
            $table->string('title', 255);
            $table->longText('body');
            $table->string('greeting', 100)->nullable();
            $table->string('signoff', 100)->nullable();
            $table->foreignId('stationery_theme_id')->nullable()->constrained('stationery_themes')->nullOnDelete();
            $table->foreignId('postage_stamp_id')->nullable()->constrained('postage_stamps')->nullOnDelete();
            $table->foreignId('wax_seal_id')->nullable()->constrained('wax_seals')->nullOnDelete();
            $table->string('status', 50)->default('draft'); // draft, queued, sent, delivered, read, archived, rejected
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->boolean('sender_deleted')->default(false);
            $table->boolean('recipient_deleted')->default(false);
            $table->timestamps();

            $table->index(['sender_id', 'status']);
            $table->index(['recipient_id', 'status']);
        });

        Schema::create('letter_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters')->cascadeOnDelete();
            $table->string('event_type', 50); // created, folded, sealed, dispatched, delivered, opened, archived
            $table->timestamp('recorded_at')->useCurrent();
            $table->json('metadata')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_events');
        Schema::dropIfExists('letters');
        Schema::dropIfExists('letter_threads');
        Schema::dropIfExists('wax_seals');
        Schema::dropIfExists('postage_stamps');
        Schema::dropIfExists('stationery_themes');
    }
};
