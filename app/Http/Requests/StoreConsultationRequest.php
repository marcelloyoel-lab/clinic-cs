<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreConsultationRequest extends FormRequest
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

            'notes' => [
                'required',
                'string',
                'max:5000',
            ],

            // Diagnoses
            'diagnoses' => [
                'nullable',
                'array',
            ],
            'diagnoses.*' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Medicines
            'medicine_id' => [
                'nullable',
                'array',
            ],
            'medicine_id.*' => [
                'nullable',
                'integer',
                Rule::exists('medicines', 'id'),
            ],

            'quantity' => [
                'nullable',
                'array',
            ],
            'quantity.*' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'instruction' => [
                'nullable',
                'array',
            ],
            'instruction.*' => [
                'nullable',
                'string',
                'max:120'
            ],

            // Treatments
            'treatment_id' => [
                'nullable',
                'array',
            ],
            'treatment_id.*' => [
                'nullable',
                'integer',
                Rule::exists('treatments', 'id'),
            ],

            'treatment_qty' => [
                'nullable',
                'array',
            ],
            'treatment_qty.*' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {

                // Medicine arrays must have same length
                if (
                    count($this->medicine_id ?? []) !== count($this->quantity ?? []) ||
                    count($this->medicine_id ?? []) !== count($this->instruction ?? [])
                ) {
                    $validator->errors()->add(
                        'medicine_id',
                        'Medicine data is invalid.'
                    );
                }

                // Treatment arrays must have same length
                if (
                    count($this->treatment_id ?? []) !== count($this->treatment_qty ?? [])
                ) {
                    $validator->errors()->add(
                        'treatment_id',
                        'Treatment data is invalid.'
                    );
                }

                foreach ($this->medicine_id ?? [] as $index => $medicineId) {

                    if (!$medicineId) {
                        continue;
                    }

                    $quantity = $this->quantity[$index] ?? null;
                    $instruction = $this->instruction[$index] ?? null;

                    if (blank($quantity)) {
                        $validator->errors()->add(
                            "quantity.$index",
                            'Medicine quantity is required.'
                        );
                    }

                    if (blank($instruction)) {
                        $validator->errors()->add(
                            "instruction.$index",
                            'Medicine instruction is required.'
                        );
                    }
                }

                foreach ($this->treatment_id ?? [] as $index => $treatmentId) {

                    if (!$treatmentId) {
                        continue;
                    }

                    $qty = $this->treatment_qty[$index] ?? null;

                    if (blank($qty)) {
                        $validator->errors()->add(
                            "treatment_qty.$index",
                            'Treatment quantity is required.'
                        );
                    }
                }
            }
        ];
    }
}