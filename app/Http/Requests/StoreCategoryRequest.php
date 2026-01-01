<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'color' => 'nullable|string|hex_color|max:7',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('app.validation.required', ['attribute' => __('app.common.name')]),
            'name.unique' => __('app.validation.unique', ['attribute' => __('app.common.name')]),
            'color.hex_color' => __('app.validation.hex_color', ['attribute' => __('app.common.color')]),
        ];
    }
}
