<?php

namespace App\Services;

use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeAccessService
{
    public function ensureAccessible(
        Request $request,
        Challenge $challenge
    ): void {
        if (
            $challenge->status !== 'published'
            && ! $request->user()?->isAdmin()
        ) {
            abort(404);
        }
    }
}
