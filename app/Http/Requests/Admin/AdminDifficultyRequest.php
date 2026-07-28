<?php

namespace App\Http\Requests\Admin;

use App\Models\Difficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminDifficultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $difficulty = $this->route('difficulty');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('difficulties', 'name')
                    ->ignore(
                        $difficulty instanceof Difficulty
                            ? $difficulty->id
                            : null,
                    ),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('difficulties', 'slug')
                    ->ignore(
                        $difficulty instanceof Difficulty
                            ? $difficulty->id
                            : null,
                    ),
            ],
            'base_points' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}
