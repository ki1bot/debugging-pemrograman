<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'challenge_solutions',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('challenge_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->longText('solution_code');

                $table->string('solution_type', 20)
                    ->default('alternative');

                $table->json('required_keywords')
                    ->nullable();

                $table->timestamps();

                $table->index([
                    'challenge_id',
                    'solution_type',
                ]);
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_solutions');
    }
};
