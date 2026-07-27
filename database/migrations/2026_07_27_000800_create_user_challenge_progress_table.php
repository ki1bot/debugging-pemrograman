<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'user_challenge_progress',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('challenge_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('best_submission_id')
                    ->nullable()
                    ->constrained('submissions')
                    ->nullOnDelete();

                $table->unsignedInteger('best_score')
                    ->default(0);

                $table->unsignedInteger('attempts_count')
                    ->default(0);

                $table->unsignedSmallInteger('hints_used')
                    ->default(0);

                $table->unsignedSmallInteger('hint_penalty')
                    ->default(0);

                $table->json('unlocked_hint_ids')
                    ->nullable();

                $table->boolean('is_completed')
                    ->default(false)
                    ->index();

                $table->timestamp('completed_at')
                    ->nullable();

                $table->timestamps();

                $table->unique([
                    'user_id',
                    'challenge_id',
                ]);

                $table->index([
                    'user_id',
                    'best_score',
                ]);
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'user_challenge_progress',
        );
    }
};
