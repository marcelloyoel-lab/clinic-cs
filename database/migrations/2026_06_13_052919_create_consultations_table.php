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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('patient_name');

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->text('chief_complaint');

            $table->tinyInteger('status');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
