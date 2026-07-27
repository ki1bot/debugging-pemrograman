<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Difficulty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'base_points',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_points' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class);
    }
}
