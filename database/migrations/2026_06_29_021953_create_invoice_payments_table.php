<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('receipt_number')->unique();

            $table->tinyInteger('payment_method');
            $table->tinyInteger('status');

            $table->decimal('amount', 15, 2)->unsigned();

            $table->string('gateway_name')->nullable();
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->string('gateway_reference')->nullable();
            $table->string('external_invoice_id')->nullable();

            $table->json('gateway_response')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
