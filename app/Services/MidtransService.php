<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransService
{
  // Configure SDK only ya disini
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        Config::$overrideNotifUrl = config('midtrans.notification_url');
    }

    /**
     * Create Midtrans Snap Token.
     */
    public function createSnapToken(array $payload): string
    {
        try {

            Log::info('Override URL', [
                'url' => Config::$overrideNotifUrl,
            ]);

            return Snap::getSnapToken($payload);

        } catch (\Throwable $e) {

            Log::error('Failed to create Midtrans Snap token.', [
                'order_id' => $payload['transaction_details']['order_id'] ?? null,
                'gross_amount' => $payload['transaction_details']['gross_amount'] ?? null,
                'customer_name' => $payload['customer_details']['first_name'] ?? null,
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
     * Verify Midtrans signature.
     */
    public function verifySignature(Notification $notification): bool
    {
        $expectedSignature = hash(
            'sha512',
            $notification->order_id .
            $notification->status_code .
            $notification->gross_amount .
            config('midtrans.server_key')
        );

        return hash_equals(
            $expectedSignature,
            $notification->signature_key
        );
    }

    /**
     * Resolve Midtrans payment method.
     */
    public function resolvePaymentMethod(
        Notification $notification
    ): PaymentMethod {

        return match ($notification->payment_type) {

            'qris' => PaymentMethod::QRIS,

            'gopay' => PaymentMethod::GOPAY,

            'shopeepay' => PaymentMethod::SHOPEEPAY,

            'credit_card' => PaymentMethod::CREDIT_CARD,

            'bank_transfer' => match (
                strtolower($notification->va_numbers[0]->bank ?? '')
            ) {
                'bca' => PaymentMethod::BCA_VA,
                'bni' => PaymentMethod::BNI_VA,
                'bri' => PaymentMethod::BRI_VA,
                default => throw new \RuntimeException(
                    'Unsupported bank transfer.'
                ),
            },

            'echannel' => PaymentMethod::MANDIRI_BILL,

            'permata' => PaymentMethod::PERMATA_VA,

            default => throw new \RuntimeException(
                'Unsupported payment type: ' .
                $notification->payment_type
            ),

        };

    }
}