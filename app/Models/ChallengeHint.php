<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeHint extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'hint_order',
        'content',
        'point_penalty',
    ];

    protected function casts(): array
    {
        return [
            'hint_order' => 'integer',
            'point_penalty' => 'integer',
        ];
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }
}
