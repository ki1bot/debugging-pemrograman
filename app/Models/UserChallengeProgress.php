<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChallengeProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'best_submission_id',
        'best_score',
        'attempts_count',
        'hints_used',
        'hint_penalty',
        'unlocked_hint_ids',
        'is_completed',
        'completed_at',
    ];

    protected $attributes = [
        'best_score' => 0,
        'attempts_count' => 0,
        'hints_used' => 0,
        'hint_penalty' => 0,
        'unlocked_hint_ids' => '[]',
        'is_completed' => false,
    ];

    protected function casts(): array
    {
        return [
            'best_submission_id' => 'integer',
            'best_score' => 'integer',
            'attempts_count' => 'integer',
            'hints_used' => 'integer',
            'hint_penalty' => 'integer',
            'unlocked_hint_ids' => 'array',
            'is_completed' => 'boolean',
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

    public function bestSubmission(): BelongsTo
    {
        return $this->belongsTo(
            Submission::class,
            'best_submission_id',
        );
    }
}
