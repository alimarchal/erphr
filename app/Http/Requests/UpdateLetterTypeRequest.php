<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLetterTypeRequest extends FormRequest
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
        $letterTypeId = $this->route('letter_type')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:letter_types,name,'.$letterTypeId],
            'code' => ['required', 'string', 'max:50', 'unique:letter_types,code,'.$letterTypeId],
            'requires_reply' => ['boolean'],
            'default_days_to_reply' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The letter type name is required.',
            'name.unique' => 'This letter type name already exists.',
            'code.required' => 'The letter type code is required.',
            'code.unique' => 'This letter type code already exists.',
        ];
    }
}
