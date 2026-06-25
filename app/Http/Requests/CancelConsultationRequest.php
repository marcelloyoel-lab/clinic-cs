<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CancelConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'consultation_id' => [
                'required',
                'integer',
                Rule::exists('consultations', 'id'),
            ],

            'cancel_reason' => [
                'required',
                'string',
                'max:300',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'consultation_id.required' => 'Consultation ID is required.',
            'consultation_id.exists' => 'Consultation not found.',

            'cancel_reason.required' => 'Cancellation reason is required.',
            'cancel_reason.max' => 'Cancellation reason may not exceed 300 characters.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withInput()
                ->with('error', $validator->errors()->first())
        );
    }
}