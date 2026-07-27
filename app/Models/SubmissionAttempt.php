<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'attempt_number',
        'line_correct',
        'code_correct',
        'matched_keywords',
        'missing_keywords',
        'score_snapshot',
        'status_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'line_correct' => 'boolean',
            'code_correct' => 'boolean',
            'matched_keywords' => 'array',
            'missing_keywords' => 'array',
            'score_snapshot' => 'integer',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
