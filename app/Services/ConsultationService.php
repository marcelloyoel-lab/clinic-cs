<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\ConsultationStatus;
use App\Models\Consultation;
use App\Models\ConsultationDiagnose;
use App\Models\ConsultationPrescription;
use App\Models\ConsultationTreatment;
use App\Models\Medicine;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class ConsultationService
{
    public function completeConsultation(array $data): Consultation
    {
        DB::beginTransaction();

        try {

            $consultation = Consultation::findOrFail(
                $data['consultation_id']
            );

            $medicines = Medicine::query()
                ->whereIn('id', $data['medicine_id'] ?? [])
                ->get()
                ->keyBy('id');

            $treatments = Treatment::query()
                ->whereIn('id', $data['treatment_id'] ?? [])
                ->get()
                ->keyBy('id');

            /*
            |--------------------------------------------------------------------------
            | Diagnoses
            |--------------------------------------------------------------------------
            */

            foreach ($data['diagnoses'] ?? [] as $diagnosis) {

                if (blank($diagnosis)) {
                    continue;
                }

                ConsultationDiagnose::create([
                    'consultation_id' => $consultation->id,
                    'diagnose_name' => trim($diagnosis),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Prescriptions
            |--------------------------------------------------------------------------
            */

            foreach ($data['medicine_id'] ?? [] as $index => $medicineId) {

                $medicine = $medicines->get($medicineId);

                if (!$medicine) {
                    throw new RuntimeException(
                        "Medicine [{$medicineId}] not found."
                    );
                }

                ConsultationPrescription::create([
                    'consultation_id' => $consultation->id,
                    'medicine_id' => $medicine->id,
                    'medicine_name' => $medicine->name,
                    'quantity' => $data['quantity'][$index],
                    'instruction' => $data['instruction'][$index],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Treatments
            |--------------------------------------------------------------------------
            */

            foreach ($data['treatment_id'] ?? [] as $index => $treatmentId) {

                $treatment = $treatments->get($treatmentId);

                if (!$treatment) {
                    throw new RuntimeException(
                        "Treatment [{$treatmentId}] not found."
                    );
                }

                ConsultationTreatment::create([
                    'consultation_id' => $consultation->id,
                    'treatment_id' => $treatment->id,
                    'treatment_name' => $treatment->name,
                    'quantity' => $data['treatment_qty'][$index],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Consultation
            |--------------------------------------------------------------------------
            */

            $consultation->update([
                'notes' => $data['notes'],
                'status' => ConsultationStatus::PAYMENT,
            ]);

            DB::commit();

            return $consultation;

        } catch (Throwable $e) {

            DB::rollBack();

            throw new RuntimeException(
                'Failed to complete consultation. ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    public function cancelConsultation(array $data): Consultation
    {
        DB::beginTransaction();

        try {

            $consultation = Consultation::with('booking')
                ->findOrFail($data['consultation_id']);

            $consultation->update([
                'status' => ConsultationStatus::CANCELLED,
                'cancel_reason' => trim($data['cancel_reason']),
            ]);

            $consultation->booking->update([
                'status' => BookingStatus::CANCELLED,
            ]);

            DB::commit();

            return $consultation;

        } catch (Throwable $e) {

            DB::rollBack();

            throw new RuntimeException(
                'Failed to cancel consultation. ' . $e->getMessage(),
                previous: $e
            );
        }
    }
}