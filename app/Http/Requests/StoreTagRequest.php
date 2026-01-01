<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
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
                Rule::unique('tags')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'color' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('app.validation.required', ['attribute' => __('app.common.name')]),
            'name.unique' => __('app.validation.unique', ['attribute' => __('app.common.name')]),
            'color.required' => __('app.validation.required', ['attribute' => __('app.common.color')]),
        ];
    }
}
