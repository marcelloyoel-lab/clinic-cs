<?php

namespace App\Services;

use App\Enums\BookingType;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScheduleUpdateService
{
    /**
     * Update booking schedule.
     *
     * @throws Throwable
     */
    public function update(Booking $booking, array $data): void
    {
        DB::beginTransaction();

        try {

            Log::info('Starting schedule update.', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'patient_id' => $booking->consultation->patient->id,
            ]);

            $this->updatePatient($booking, $data);

            $this->updateConsultation($booking, $data);

            $this->updateBooking($booking, $data);

            DB::commit();

            Log::info('Schedule updated successfully.', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
            ]);

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Failed to update schedule.', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'patient_id' => $booking->consultation->patient->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    /**
     * Update patient information.
     */
    private function updatePatient(Booking $booking, array $data): void
    {
        $booking->consultation->patient->update([
            'name' => $data['name'],
            'dob' => $data['dob'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'phone_alternative' => $data['phone_alternative'] ?? null,
            'email' => $data['email'] ?? null,
        ]);

        Log::info('Patient information updated.', [
            'patient_id' => $booking->consultation->patient->id,
        ]);
    }

    /**
     * Update consultation information.
     */
    private function updateConsultation(Booking $booking, array $data): void
    {
        $booking->consultation->update([
            'doctor_id' => $data['doctor_id'],
            'patient_name' => $data['name'],
            'chief_complaint' => $data['chief_complaint'],
        ]);

        Log::info('Consultation information updated.', [
            'consultation_id' => $booking->consultation->id,
        ]);
    }

    /**
     * Update booking information.
     */
    private function updateBooking(Booking $booking, array $data): void
    {
        $booking->update([
            'type' => match ($data['booking_type']) {
                'consultation' => BookingType::CONSULTATION,
                'treatment' => BookingType::TREATMENT,
            },
            'date' => $data['date'],
            'time' => $data['time'],
        ]);

        Log::info('Booking information updated.', [
            'booking_id' => $booking->id,
        ]);
    }
}