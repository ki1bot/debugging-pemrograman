<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'submissions',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('challenge_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->unsignedInteger('selected_line');
                $table->longText('submitted_code');
                $table->text('submitted_explanation');

                $table->unsignedInteger('line_score')
                    ->default(0);

                $table->unsignedInteger('code_score')
                    ->default(0);

                $table->unsignedInteger('explanation_score')
                    ->default(0);

                $table->unsignedSmallInteger('hint_penalty')
                    ->default(0);

                $table->unsignedInteger('final_score')
                    ->default(0);

                $table->string('status', 30)
                    ->default('incorrect')
                    ->index();

                $table->timestamp('completed_at')
                    ->nullable();

                $table->timestamps();

                $table->index([
                    'user_id',
                    'challenge_id',
                ]);

                $table->index([
                    'challenge_id',
                    'final_score',
                ]);
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
