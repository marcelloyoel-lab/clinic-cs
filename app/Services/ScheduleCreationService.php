<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\ConsultationStatus;
use App\Models\Booking;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ScheduleCreationService
{
    public function create(array $data): Consultation
    {
        return DB::transaction(function () use ($data) {

            $patient = $this->resolvePatient($data);

            $booking = $this->createBooking($data);

            return Consultation::create([
                'booking_id' => $booking->id,
                'patient_name' => $patient->name,
                'patient_id' => $patient->id,
                'doctor_id' => $data['doctor_id'],
                'chief_complaint' => $data['chief_complaint'],
                'notes' => $data['notes'] ?? null,
                'status' => ConsultationStatus::DRAFT,
                'created_by' => Auth::id(),
            ]);
        });
    }

    private function resolvePatient(array $data): Patient
    {
        $patientData = [
            'name' => $data['name'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'phone_alternative' => $data['phone_alternative'] ?? null,
            'email' => $data['email'] ?? null,
            'dob' => $data['dob'] ?? null,
        ];

        if ((int) $data['patient_id'] === -1) {
            $patientData['created_by'] = Auth::id();

            return Patient::create($patientData);
        }

        $patient = Patient::findOrFail($data['patient_id']);

        $patient->fill($patientData);

        if ($patient->isDirty()) {
            $patient->save();
        }

        return $patient;
    }

    private function createBooking(array $data): Booking
    {
        return Booking::create([
            'type' => BookingType::CONSULTATION,
            'date' => $data['date'],
            'time' => $data['time'],
            'booking_code' => $this->generateBookingCode(
                BookingType::CONSULTATION,
                $data['date']
            ),
            'status' => BookingStatus::BOOKED,
            'created_by' => Auth::id(),
        ]);
    }

    private function generateBookingCode(
        BookingType $type,
        string $date
    ): string {
        $prefix = match ($type) {
            BookingType::CONSULTATION => 'CONS',
            BookingType::TREATMENT => 'TRT',
            BookingType::REPURCHASING => 'REP',
        };

        $runningNumber = Booking::query()
            ->where('type', $type)
            ->whereDate('date', $date)
            ->count() + 1;

        return sprintf(
            '%s-%s-%04d',
            $prefix,
            date('Ymd', strtotime($date)),
            $runningNumber
        );
    }
}