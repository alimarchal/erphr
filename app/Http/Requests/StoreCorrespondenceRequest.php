<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCorrespondenceRequest extends FormRequest
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
        $isReceipt = $this->input('type') === 'Receipt';

        return [
            'type' => ['required', Rule::in(['Receipt', 'Dispatch'])],
            'letter_type_id' => ['nullable', 'exists:letter_types,id'],
            'category_id' => ['nullable', 'exists:correspondence_categories,id'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'letter_date' => ['nullable', 'date'],
            'received_date' => ['nullable', 'date', 'required_if:type,Receipt'],
            'dispatch_date' => ['nullable', 'date', 'required_if:type,Dispatch'],
            'subject' => ['required', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'sender_name' => ['nullable', 'string', 'max:500'],
            'from_division_id' => ['nullable', 'exists:divisions,id'],
            'to_division_id' => $isReceipt ? ['nullable', 'exists:divisions,id'] : ['nullable'],
            'region_id' => ['nullable', 'exists:regions,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'marked_to_user_id' => $isReceipt ? ['nullable', 'exists:users,id'] : ['nullable'],
            'addressed_to_user_id' => ['nullable', 'exists:users,id'],
            'initial_action' => ['nullable', 'string', 'max:100'],
            'status_id' => ['nullable', 'exists:correspondence_statuses,id'],
            'priority_id' => ['nullable', 'exists:correspondence_priorities,id'],
            'confidentiality' => ['nullable', Rule::in(['Normal', 'Confidential', 'Secret', 'TopSecret'])],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'delivery_mode' => ['nullable', Rule::in(['Hand', 'Courier', 'Post', 'Email', 'Fax', 'Other'])],
            'courier_name' => ['nullable', 'string', 'max:255'],
            'courier_tracking' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:15360'], // 15MB max per file
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
            'type.required' => 'Please select whether this is a Receipt or Dispatch.',
            'subject.required' => 'The subject is required.',
            'subject.max' => 'The subject cannot exceed 1000 characters.',
            'received_date.required_if' => 'The received date is required for receipts.',
            'dispatch_date.required_if' => 'The dispatch date is required for dispatches.',
            'due_date.after_or_equal' => 'The due date must be today or a future date.',
            'attachments.*.max' => 'Each attachment must not exceed 15MB.',
        ];
    }
}
