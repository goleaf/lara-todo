<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|string|in:pending,completed',
            'due_date' => 'nullable|date',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'progress' => 'nullable|integer|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('app.validation.required', ['attribute' => __('app.common.title')]),
            'category_id.required' => __('app.validation.required', ['attribute' => __('app.common.category')]),
            'status.in' => __('app.validation.in', ['attribute' => __('app.common.status')]),
        ];
    }
}
