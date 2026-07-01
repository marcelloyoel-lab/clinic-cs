<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Consultation;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransController extends Controller
{
    protected MidtransService $midtransService;
    protected PaymentService $paymentService;

    public function __construct(
        MidtransService $midtransService,
        PaymentService $paymentService
    ) {
        $this->midtransService = $midtransService;
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Midtrans Webhook.
     */
    public function notification(Request $request)
    {
        try {

            Log::info('MIDTRANS WEBHOOK HIT');

            /*
            |--------------------------------------------------------------------------
            | Parse Notification
            |--------------------------------------------------------------------------
            */

            Log::info('RAW WEBHOOK', [
                'body' => $request->getContent(),
            ]);

            $notification = new Notification();

            /*
            |--------------------------------------------------------------------------
            | Verify Signature
            |--------------------------------------------------------------------------
            */

            if (! $this->midtransService->verifySignature($notification)) {

                Log::warning('Invalid Midtrans signature.', [
                    'order_id' => $notification->order_id ?? null,
                    'transaction_id' => $notification->transaction_id ?? null,
                ]);

                return response()->json([
                    'message' => 'Invalid signature.'
                ], 403);

            }

            /*
            |--------------------------------------------------------------------------
            | Only process successful payment
            |--------------------------------------------------------------------------
            */

            if (! in_array($notification->transaction_status, [
                'capture',
                'settlement'
            ])) {

                Log::info('Ignoring Midtrans notification.', [
                    'order_id' => $notification->order_id,
                    'transaction_status' => $notification->transaction_status,
                ]);

                return response()->json([
                    'message' => 'Ignored.'
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Resolve Payment Method
            |--------------------------------------------------------------------------
            */

            $paymentMethod = $this->midtransService
                ->resolvePaymentMethod($notification);

            /*
            |--------------------------------------------------------------------------
            | Resolve Consultation
            |--------------------------------------------------------------------------
            */

            preg_match(
                '/^INV-CON-(\d+)-/',
                $notification->order_id,
                $matches
            );

            $consultationId = (int) $matches[1];

            $consultation = Consultation::with([
                'booking',
                'patient',
                'consultationPrescription.medicine',
                'consultationTreatment.treatment',
            ])
            ->findOrFail($consultationId);

            /*
            |--------------------------------------------------------------------------
            | Complete Payment
            |--------------------------------------------------------------------------
            */

            $this->paymentService->completeSuccessfulPayment(
                $consultation,
                (array) $notification,
                $paymentMethod
            );

            return response()->json([
                'message' => 'OK'
            ]);

        } catch (\Throwable $e) {

            Log::error('Failed to process Midtrans webhook.', [
                'order_id' => $notification->order_id ?? null,
                'transaction_id' => $notification->transaction_id ?? null,
                'consultation_id' => $consultationId ?? null,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Internal server error.'
            ], 500);

        }
    }
}