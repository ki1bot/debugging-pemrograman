<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'selected_line',
        'submitted_code',
        'submitted_explanation',
        'line_score',
        'code_score',
        'explanation_score',
        'hint_penalty',
        'final_score',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'selected_line' => 'integer',
            'line_score' => 'integer',
            'code_score' => 'integer',
            'explanation_score' => 'integer',
            'hint_penalty' => 'integer',
            'final_score' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(SubmissionAttempt::class);
    }
}
