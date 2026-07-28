<?php

namespace App\Http\Requests\Admin;

use App\Models\Challenge;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AdminChallengeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $challenge = $this->route('challenge');

        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'difficulty_id' => [
                'required',
                'integer',
                'exists:difficulties,id',
            ],
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                'alpha_dash',
                Rule::unique('challenges', 'slug')
                    ->ignore(
                        $challenge instanceof Challenge
                            ? $challenge->id
                            : null,
                    ),
            ],
            'description' => [
                'required',
                'string',
                'max:2000',
            ],
            'broken_code' => [
                'required',
                'string',
                'max:20000',
            ],
            'buggy_line' => [
                'required',
                'integer',
                'min:1',
            ],
            'explanation' => [
                'required',
                'string',
                'max:5000',
            ],
            'base_points' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],
            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'inactive',
                ]),
            ],
            'hints' => [
                'required',
                'array',
                'min:1',
                'max:5',
            ],
            'hints.*.content' => [
                'required',
                'string',
                'max:2000',
            ],
            'hints.*.point_penalty' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
            'solutions' => [
                'required',
                'array',
                'min:1',
                'max:10',
            ],
            'solutions.*.solution_code' => [
                'required',
                'string',
                'max:20000',
            ],
            'solutions.*.solution_type' => [
                'required',
                Rule::in([
                    'primary',
                    'alternative',
                ]),
            ],
            'solutions.*.required_keywords' => [
                'nullable',
                'array',
                'max:20',
            ],
            'solutions.*.required_keywords.*' => [
                'string',
                'max:100',
            ],
        ];
    }

    public function withValidator(
        Validator $validator
    ): void {
        $validator->after(
            function (Validator $validator): void {
                if (
                    ! $validator->errors()->has('broken_code')
                    && ! $validator->errors()->has('buggy_line')
                ) {
                    $lineCount = count(
                        preg_split(
                            '/\r\n|\r|\n/',
                            (string) $this->input('broken_code'),
                        ),
                    );

                    if (
                        (int) $this->input('buggy_line')
                        > $lineCount
                    ) {
                        $validator->errors()->add(
                            'buggy_line',
                            'Nomor baris bug melebihi jumlah baris kode.',
                        );
                    }
                }

                if (
                    ! $validator->errors()->has('solutions')
                    && ! collect($validator->errors()->keys())
                        ->contains(
                            fn (string $key) => str_starts_with(
                                $key,
                                'solutions.',
                            ),
                        )
                ) {
                    $primaryCount = collect(
                        $this->input('solutions', []),
                    )
                        ->where(
                            'solution_type',
                            'primary',
                        )
                        ->count();

                    if ($primaryCount !== 1) {
                        $validator->errors()->add(
                            'solutions',
                            'Tantangan harus memiliki tepat satu solusi utama.',
                        );
                    }
                }
            },
        );
    }
}
