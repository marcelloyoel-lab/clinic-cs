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
        Schema::create('consultation_treatments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consultation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('treatment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('treatment_name');
            $table->unsignedInteger('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_treatments');
    }
};
