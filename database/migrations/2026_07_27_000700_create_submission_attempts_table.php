<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'submission_attempts',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('submission_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->unsignedInteger('attempt_number');

                $table->boolean('line_correct')
                    ->default(false);

                $table->boolean('code_correct')
                    ->default(false);

                $table->json('matched_keywords')
                    ->nullable();

                $table->json('missing_keywords')
                    ->nullable();

                $table->unsignedInteger('score_snapshot')
                    ->default(0);

                $table->string('status_snapshot', 30);
                $table->timestamps();

                $table->unique([
                    'submission_id',
                    'attempt_number',
                ]);
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_attempts');
    }
};
