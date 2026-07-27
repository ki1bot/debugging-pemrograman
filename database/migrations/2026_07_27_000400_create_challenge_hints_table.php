<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'challenge_hints',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('challenge_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->unsignedSmallInteger('hint_order');
                $table->text('content');

                $table->unsignedSmallInteger('point_penalty')
                    ->default(10);

                $table->timestamps();

                $table->unique([
                    'challenge_id',
                    'hint_order',
                ]);
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_hints');
    }
};
