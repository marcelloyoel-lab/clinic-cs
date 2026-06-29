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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->tinyInteger('item_type');

            $table->foreignId('consultation_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('medicine_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('treatment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('description');

            $table->decimal('unit_price', 15, 2)->unsigned();

            $table->unsignedInteger('quantity');
            $table->unsignedInteger('remaining_quantity');

            $table->decimal('line_total', 15, 2)->unsigned();

            $table->timestamps();

            $table->index('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
