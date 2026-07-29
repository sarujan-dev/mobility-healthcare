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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
        $table->date('appointment_date');
        $table->time('time_slot');
        $table->enum('type', ['Regular', 'Emergency'])->default('Regular');
        $table->enum('status', ['Pending', 'Confirmed', 'Cancelled', 'Completed'])->default('Pending');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('appointments');
}
};
