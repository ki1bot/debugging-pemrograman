<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Challenge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'difficulty_id',
        'title',
        'slug',
        'description',
        'broken_code',
        'buggy_line',
        'explanation',
        'base_points',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'buggy_line' => 'integer',
            'base_points' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hints(): HasMany
    {
        return $this->hasMany(ChallengeHint::class)->orderBy('hint_order');
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(ChallengeSolution::class)
            ->orderByRaw("CASE WHEN solution_type = 'primary' THEN 0 ELSE 1 END");
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(UserChallengeProgress::class);
    }
}
