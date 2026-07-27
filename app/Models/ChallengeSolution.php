<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeSolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'solution_code',
        'solution_type',
        'required_keywords',
    ];

    protected function casts(): array
    {
        return [
            'required_keywords' => 'array',
        ];
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }
}
