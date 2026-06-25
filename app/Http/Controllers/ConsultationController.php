<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\ConsultationStatus;
use App\Http\Requests\CancelConsultationRequest;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use App\Models\Medicine;
use App\Models\Treatment;
use App\Services\ConsultationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultationController extends Controller
{
    public function start($id){
        $consultation = Consultation::with([
            'patient.lastVisit',
            'booking',
            'doctor',
        ])->findOrFail($id);

        Log::info("User started consultation", ['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'consultation_id' => $consultation->id]);
        Log::info("Updating consultation and booking status to 'in_progress'", ['consultation_id' => $consultation->id]);

        $consultation->update([
            'status' => ConsultationStatus::ON_GOING,
        ]);

        $consultation->booking->update([
            'status' => BookingStatus::IN_PROGRESS,
        ]);

        // dd($consultation->patient->gender);

        $treatments = Treatment::select('id', 'name')->get();
        $medicines = Medicine::select('id', 'name')->get();

        return view('consultation.start', compact('consultation', 'treatments', 'medicines'));
    }

    public function store(StoreConsultationRequest $request, ConsultationService $consultationService){
        // dd($request->all());
        Log::info('Store Consultation Request: ', $request->all());
        Log::info("Initialized By", ['user_id' => auth()->id(), 'user_name' => auth()->user()->name]);

        try {
            $consultationService->completeConsultation(
                $request->validated()
            );

            return redirect()
                ->route('booking-list')
                ->with('success', 'Consultation completed successfully.');

        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function cancel(
        CancelConsultationRequest $request,
        ConsultationService $consultationService
    ) {
        try {

            $consultationService->cancelConsultation(
                $request->validated()
            );

            return redirect()
                ->route('booking-list')
                ->with('success', 'Consultation cancelled successfully.');

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
