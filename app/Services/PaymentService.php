<?php

namespace App\Services;

use App\Models\Consultation;
use App\Services\MidtransService;
use App\Enums\BookingStatus;
use App\Enums\ConsultationStatus;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enums\InvoiceStatus;
use App\Models\InvoiceItem;
use App\Enums\InvoiceItemType;
use App\Models\InvoicePayment;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;

class PaymentService
{

    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    /**
     * Prepare all payment-related information.
     */
    public function preparePayment(Consultation $consultation): array
    {
        $consultationFee = 750000;

        $prescriptionTotal = $consultation->consultationPrescription->sum(function ($prescription) {
            return ($prescription->medicine?->price ?? 0) * ($prescription->quantity ?? 1);
        });

        $treatmentTotal = $consultation->consultationTreatment->sum(function ($consultationTreatment) {
            return ($consultationTreatment->treatment?->price ?? 0) * ($consultationTreatment->quantity ?? 1);
        });

        $grandTotal = $consultationFee + $prescriptionTotal + $treatmentTotal;

        return [
          'invoiceNumber'     => $this->generateInvoiceNumber($consultation),

          'consultationFee'   => $consultationFee,
          'prescriptionTotal' => $prescriptionTotal,
          'treatmentTotal'    => $treatmentTotal,

          'subtotal'          => $grandTotal,
          'discount'          => 0,
          'tax'               => 0,

          'grandTotal'        => $grandTotal,
          'paidAmount'        => $grandTotal,
          'remainingAmount'   => 0,
      ];
    }

    /**
     * Generate invoice number.
     */
    public function generateInvoiceNumber(Consultation $consultation): string
    {
        return sprintf(
            'INV-CON-%d',
            $consultation->id
        );
    } 

    /**
     * Create Midtrans transaction and return Snap Token.
     */
    public function createMidtransPayment(Consultation $consultation): string
    {
        try {

            $payment = $this->preparePayment($consultation);

            $payload = [

                'transaction_details' => [

                    'order_id' => $this->generateMidtransOrderId($consultation),

                    'gross_amount' => $payment['grandTotal'],

                ],

                'customer_details' => [

                    'first_name' => $consultation->patient->name,

                ],

                'item_details' => [

                    [
                        'id'       => 'CONSULTATION',
                        'price'    => $payment['consultationFee'],
                        'quantity' => 1,
                        'name'     => 'Consultation Fee',
                    ],

                ],

            ];

            /*
            |--------------------------------------------------------------------------
            | Medicines
            |--------------------------------------------------------------------------
            */

            foreach ($consultation->consultationPrescription as $prescription) {

                $payload['item_details'][] = [

                    'id'       => 'MED-' . $prescription->medicine->id,

                    'price'    => $prescription->medicine->price,

                    'quantity' => $prescription->quantity,

                    'name'     => $prescription->medicine->name,

                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Treatments
            |--------------------------------------------------------------------------
            */

            foreach ($consultation->consultationTreatment as $treatment) {

                $payload['item_details'][] = [

                    'id'       => 'TRT-' . $treatment->treatment->id,

                    'price'    => $treatment->treatment->price,

                    'quantity' => $treatment->quantity,

                    'name'     => $treatment->treatment->name,

                ];
            }

            return $this->midtransService
                ->createSnapToken($payload);

        } catch (\Throwable $e) {

            Log::error('Failed to create Midtrans payment.', [
                'consultation_id' => $consultation->id,
                'booking_id' => $consultation->booking->id,
                'patient_id' => $consultation->patient->id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;

        }
    }

    /**
     * Complete a successful payment.
     */
    public function completeSuccessfulPayment(
        Consultation $consultation,
        array $gatewayResponse,
        PaymentMethod $paymentMethod
    ): Invoice {

        /*
        |--------------------------------------------------------------------------
        | Prepare Payment
        |--------------------------------------------------------------------------
        */

        $payment = $this->preparePayment($consultation);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Invoice
        |--------------------------------------------------------------------------
        */

        $existingInvoice = Invoice::where(
            'invoice_number',
            $payment['invoiceNumber']
        )->first();

        if ($existingInvoice) {
            return $existingInvoice;
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Create Invoice
            |--------------------------------------------------------------------------
            */

            $invoice = $this->createInvoice(
                $consultation,
                $payment
            );

            /*
            |--------------------------------------------------------------------------
            | Create Invoice Items
            |--------------------------------------------------------------------------
            */

            $this->createInvoiceItems(
                $invoice,
                $consultation,
                $payment
            );

            /*
            |--------------------------------------------------------------------------
            | Create Payment Record
            |--------------------------------------------------------------------------
            */

            $this->createInvoicePayment(
                $invoice,
                $consultation,
                $gatewayResponse,
                $paymentMethod
            );

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $consultation->booking->update([
                'status' => BookingStatus::FINISHED,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Consultation
            |--------------------------------------------------------------------------
            */

            $consultation->update([
                'status' => ConsultationStatus::FINISHED,
            ]);

            DB::commit();

            return $invoice;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to complete successful payment.', [
                'consultation_id' => $consultation->id,
                'booking_id' => $consultation->booking->id,
                'patient_id' => $consultation->patient->id,
                'invoice_number' => $payment['invoiceNumber'],
                'order_id' => $gatewayResponse['order_id'] ?? null,
                'transaction_id' => $gatewayResponse['transaction_id'] ?? null,
                'payment_method' => $paymentMethod->value,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;

        }
    }

    /**
     * Create invoice.
     */
    private function createInvoice(
        Consultation $consultation,
        array $payment
    ): Invoice {

        return Invoice::create([

            'invoice_number' => $payment['invoiceNumber'],

            'booking_id' => $consultation->booking->id,

            'patient_id' => $consultation->patient->id,

            'cashier_id' => $consultation->cashier_id,

            'status' => InvoiceStatus::PAID,

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            'subtotal' => $payment['subtotal'],

            'discount' => $payment['discount'],

            'tax' => $payment['tax'],

            'grand_total' => $payment['grandTotal'],

            'paid_amount' => $payment['paidAmount'],

            'remaining_amount' => $payment['remainingAmount'],

            /*
            |--------------------------------------------------------------------------
            | Date
            |--------------------------------------------------------------------------
            */

            'issued_at' => Carbon::now(),

        ]);
    }

    /**
     * Create invoice items.
     */
    private function createInvoiceItems(
        Invoice $invoice,
        Consultation $consultation,
        array $payment
    ): void {

        $items = [];

        /*
        |--------------------------------------------------------------------------
        | Consultation Fee
        |--------------------------------------------------------------------------
        */

        $items[] = [

            'invoice_id' => $invoice->id,

            'medicine_id' => null,

            'treatment_id' => null,

            'description' => 'Consultation Fee',

            'item_type' => InvoiceItemType::CONSULTATION,

            'quantity' => 1,

            'unit_price' => $payment['consultationFee'],

            'line_total' => $payment['consultationFee'],

            'remaining_quantity'  => 1,

            'created_at' => now(),

            'updated_at' => now(),

        ];

        /*
        |--------------------------------------------------------------------------
        | Medicines
        |--------------------------------------------------------------------------
        */

        foreach ($consultation->consultationPrescription as $prescription) {

            $items[] = [

                'invoice_id' => $invoice->id,

                'medicine_id' => $prescription->medicine->id,

                'treatment_id' => null,

                'description' => $prescription->medicine->name,

                'item_type' => InvoiceItemType::MEDICINE,

                'quantity' => $prescription->quantity,

                'remaining_quantity'  => $prescription->quantity,

                'unit_price' => $prescription->medicine->price,

                'line_total' => $prescription->medicine->price * $prescription->quantity,

                'created_at' => now(),

                'updated_at' => now(),

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Treatments
        |--------------------------------------------------------------------------
        */

        foreach ($consultation->consultationTreatment as $treatment) {

            $items[] = [

                'invoice_id' => $invoice->id,

                'medicine_id' => null,

                'treatment_id' => $treatment->treatment->id,

                'description' => $treatment->treatment->name,

                'item_type' => InvoiceItemType::TREATMENT,

                'quantity' => $treatment->quantity,

                'remaining_quantity'  => $treatment->quantity,

                'unit_price' => $treatment->treatment->price,

                'line_total' => $treatment->treatment->price * $treatment->quantity,

                'created_at' => now(),

                'updated_at' => now(),

            ];

        }

        InvoiceItem::insert($items);

    }

    /**
     * Create invoice payment.
     */
    private function createInvoicePayment(
        Invoice $invoice,
        Consultation $consultation,
        array $gatewayResponse,
        PaymentMethod $paymentMethod
    ): InvoicePayment {

        return InvoicePayment::create([

            'invoice_id' => $invoice->id,

            'receipt_number' => $this->generateReceiptNumber($invoice),

            'payment_method' => $paymentMethod,

            'status' => PaymentStatus::PAID,

            'gateway_transaction_id' => $gatewayResponse['transaction_id'] ?? null,

            'gateway_reference' => $gatewayResponse['order_id'] ?? null,

            'gateway_response' => json_encode($gatewayResponse),

            'amount' => $invoice->paid_amount,

            'paid_at' => now(),

            'created_by' => $consultation->cashier_id,

        ]);

    }

    /**
     * Generate receipt number.
     */
    private function generateReceiptNumber(
        Invoice $invoice
    ): string {

        return sprintf(
            'RCT-%s',
            $invoice->invoice_number
        );

    }

    /**
     * Generate Midtrans Order ID.
     */
    public function generateMidtransOrderId(
        Consultation $consultation
    ): string {

        return sprintf(
            'INV-CON-%d-%d',
            $consultation->id,
            now()->timestamp
        );
    }
}