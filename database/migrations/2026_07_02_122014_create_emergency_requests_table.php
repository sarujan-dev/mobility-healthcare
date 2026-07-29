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
    Schema::create('emergency_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('set null');
        $table->decimal('patient_lat', 10, 7);
        $table->decimal('patient_lng', 10, 7);
        $table->decimal('distance_km', 6, 2)->nullable();
        $table->enum('status', ['Pending', 'Accepted', 'Completed', 'Cancelled'])->default('Pending');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('emergency_requests');
}
};
