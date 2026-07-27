<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'challenges',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('category_id')
                    ->constrained()
                    ->restrictOnDelete();

                $table->foreignId('difficulty_id')
                    ->constrained()
                    ->restrictOnDelete();

                $table->string('title', 150);
                $table->string('slug', 180)->unique();
                $table->text('description');
                $table->longText('broken_code');
                $table->unsignedInteger('buggy_line');
                $table->longText('explanation');
                $table->unsignedInteger('base_points');

                $table->string('status', 20)
                    ->default('draft')
                    ->index();

                $table->foreignId('created_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();

                $table->index([
                    'category_id',
                    'difficulty_id',
                    'status',
                ]);
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
