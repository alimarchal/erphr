<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCorrespondenceCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('correspondence_category')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:correspondence_categories,name,'.$categoryId],
            'code' => ['nullable', 'string', 'max:20', 'unique:correspondence_categories,code,'.$categoryId],
            'parent_id' => ['nullable', 'exists:correspondence_categories,id', 'not_in:'.$categoryId],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.max' => 'The category name cannot exceed 255 characters.',
            'code.max' => 'The category code cannot exceed 20 characters.',
            'code.unique' => 'A category with this code already exists.',
            'parent_id.exists' => 'The selected parent category does not exist.',
            'parent_id.not_in' => 'A category cannot be its own parent.',
        ];
    }
}
