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
    Schema::create('doctors', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('hospital_id')->nullable()->constrained()->onDelete('set null');
        $table->enum('doctor_type', ['VP', 'VOG']);
        $table->string('specialization')->nullable();
        $table->string('qualifications')->nullable();
        $table->text('about')->nullable();
        $table->enum('availability_status', ['Available', 'Busy', 'In OPD', 'On Leave', 'Off Duty'])->default('Available');
        $table->string('profile_photo')->nullable();
        $table->decimal('rating', 2, 1)->default(0);
        $table->decimal('lat', 10, 7)->nullable();
        $table->decimal('lng', 10, 7)->nullable();
        $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('doctors');
}
};
