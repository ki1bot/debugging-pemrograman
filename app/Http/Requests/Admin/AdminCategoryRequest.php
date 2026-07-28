<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')
                    ->ignore(
                        $category instanceof Category
                            ? $category->id
                            : null,
                    ),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('categories', 'slug')
                    ->ignore(
                        $category instanceof Category
                            ? $category->id
                            : null,
                    ),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }
}
