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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_number')->unique();

            $table->foreignId('booking_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('cashier_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->tinyInteger('status');

            $table->decimal('subtotal', 15, 2)->unsigned();
            $table->decimal('discount', 15, 2)->unsigned()->default(0);
            $table->decimal('tax', 15, 2)->unsigned()->default(0);
            $table->decimal('grand_total', 15, 2)->unsigned();

            $table->decimal('paid_amount', 15, 2)->unsigned()->default(0);
            $table->decimal('remaining_amount', 15, 2)->unsigned();

            $table->timestamp('issued_at');

            $table->timestamps();

            $table->index('status');
            $table->index('issued_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
