<?php

namespace App\Http\Requests;

use App\Enums\BookingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScheduleRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $booking = $this->route('booking');
        $patientId = $booking->consultation->patient->id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Patient Information
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'dob' => [
                'required',
                'date',
            ],

            'gender' => [
                'required',
                Rule::in([0, 1]),
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('patients', 'phone')
                    ->ignore($patientId),
            ],

            'phone_alternative' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('patients', 'email')
                    ->ignore($patientId),
            ],

            /*
            |--------------------------------------------------------------------------
            | Booking Information
            |--------------------------------------------------------------------------
            */

            'booking_type' => [
                'required',
                Rule::in(['consultation']),
            ],

            'doctor_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('role_id', 4),
            ],

            'date' => [
                'required',
                'date',
            ],

            'time' => [
                'required',
                'date_format:H:i',
            ],

            'chief_complaint' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Patient Information
            |--------------------------------------------------------------------------
            */

            'name.required' => 'Patient name is required.',
            'name.max' => 'Patient name may not be greater than 255 characters.',

            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Invalid date of birth.',

            'gender.required' => 'Gender is required.',
            'gender.in' => 'Invalid gender selection.',

            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number may not be greater than 20 characters.',
            'phone.unique' => 'This phone number is already registered.',

            'phone_alternative.max' => 'Alternative phone number may not be greater than 20 characters.',

            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email may not be greater than 255 characters.',
            'email.unique' => 'This email address is already registered.',

            /*
            |--------------------------------------------------------------------------
            | Booking Information
            |--------------------------------------------------------------------------
            */

            'booking_type.required' => 'Please select a booking type.',
            'booking_type.in' => 'Only consultation booking can be edited.',

            'doctor_id.required' => 'Please select an attending doctor.',
            'doctor_id.exists' => 'The selected doctor does not exist.',

            'date.required' => 'Appointment date is required.',
            'date.date' => 'Invalid appointment date.',

            'time.required' => 'Appointment time is required.',
            'time.date_format' => 'Invalid appointment time.',

            'chief_complaint.required' => 'Reason for visit is required.',
            'chief_complaint.max' => 'Reason for visit may not be greater than 1000 characters.',
        ];
    }
}