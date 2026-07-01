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
use App\Services\PaymentService;

class ConsultationController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

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

    public function payment(Consultation $consultation)
    {
        // Eager load all required relationships to prevent N+1 queries
        $consultation->load([
            'patient:id,name',
            'booking:id,date,time,booking_code',
            'consultationDiagnose',
            'consultationPrescription.medicine:id,name,price', // Assuming medicine table has 'price'
            'consultationTreatment.treatment:id,name,price'    // Assuming treatment table has 'price'
        ]);

        $payment = $this->paymentService->preparePayment($consultation);

        return view('cashier.payment', [
            'consultation' => $consultation,
            ...$payment,
        ]);
    }

    public function payment_submit(
        Request $request,
        Consultation $consultation
    ) {
        try {

            $validated = $request->validate([
                'payment_method' => 'required|in:cash,midtrans',
            ]);

            $consultation->load([
                'patient:id,name',
                'booking:id,date,time,booking_code',
                'consultationPrescription.medicine:id,name,price',
                'consultationTreatment.treatment:id,name,price',
            ]);

            /*
            |--------------------------------------------------------------------------
            | CASH
            |--------------------------------------------------------------------------
            */

            if ($validated['payment_method'] === 'cash') {

                // TODO:
                // Save payment to database

                return response()->json([
                    'success' => true,
                    'message' => 'Cash payment is not implemented yet.',
                ]);
            }

            $consultation->update([
                'cashier_id' => auth()->id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | MIDTRANS
            |--------------------------------------------------------------------------
            */

            $snapToken = $this->paymentService
                ->createMidtransPayment($consultation);

            return response()->json([
                'success' => true,
                'payment_method' => 'midtrans',
                'snap_token' => $snapToken,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::warning('Payment validation failed.', [
                'consultation_id' => $consultation->id,
                'user_id' => auth()->id(),
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            Log::error('Failed to initiate payment.', [
                'consultation_id' => $consultation->id,
                'booking_id' => $consultation->booking_id,
                'user_id' => auth()->id(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to process payment. Please try again.',
            ], 500);

        }
    }
}
